<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Entity\News;
use Avl\UserBundle\Form\Type\NewsType;
use Avl\UserBundle\Form\Type\SubUserSearchFormType;
use Symfony\Component\HttpFoundation\Request;
use Avl\UserBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * News controller.
 */
class NewsController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_SUBUSER_MANAGER'));

        $form = $this->createForm(new SubUserSearchFormType());
        $form->submit($request);

        $query = $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:News')
            ->getAllNewsByQuery(
                $form->getData()
            );

        $entities = $this->get('knp_paginator')
            ->paginate(
                $query,
                $request->query->get('page', 1),
                5
            );

        return $this->render('UserBundle:News:list.news.html.twig', array(
            'entities' => $entities,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_SUBUSER_MANAGER'));

        $entity = new News($this->getUser());
        $form = $form = $this->createForm(new NewsType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'news.flash.create.success');
            return $this->redirect($this->generateUrl('avl_news', array('newsId' => $entity->getId())));
        }

        return $this->render('UserBundle:News:edit.news.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @param $newsId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($newsId)
    {
        $entity = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:News')
            ->find($newsId);

        $entityCategorys = $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:NewsCategorys')
            ->findBy(
                array(
                    'enabled' => true,
                    'internal' => true
                ),
                array('name' => 'ASC')
            );

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        return $this->render('UserBundle:News:show.news.html.twig', array(
            'entity' => $entity,
            'entityCategorys' => $entityCategorys
        ));
    }

    /**
     * @param Request $request
     * @param $newsId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $newsId)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_SUBUSER_MANAGER'));

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UserBundle:News')->find($newsId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        if (null === $entity->getUser()) {
            $entity->setUser($this->getUser());
        }

        $form = $this->createForm(new NewsType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'news.flash.edit.success');
            return $this->redirect($this->generateUrl('avl_news', array('newsId' => $newsId)));
        }

        return $this->render('UserBundle:News:edit.news.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $newsId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $newsId)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_SUBUSER_MANAGER'));

        if ($request->getMethod() == 'DELETE') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:News')->find($newsId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find News entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'news.flash.remove.success');
        }

        return $this->redirect($this->generateUrl('avl_news'));
    }
}
