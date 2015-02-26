<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Entity\NewsCategorys;
use Avl\UserBundle\Form\Type\NewsCategorysType;
use Avl\UserBundle\Form\Type\SubUserSearchFormType;
use Symfony\Component\HttpFoundation\Request;
use Avl\UserBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * News controller.
 */
class NewsCategorysController extends BaseController
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
            ->getRepository('UserBundle:NewsCategorys')
            ->getAllNewsCategorysByQuery(
                $form->getData()
            );

        $entities = $this->get('knp_paginator')
            ->paginate(
                $query,
                $request->query->get('page', 1),
                5
            );

        return $this->render('UserBundle:News:list.categorys.html.twig', array(
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

        $entity = new NewsCategorys();
        $form = $form = $this->createForm(new NewsCategorysType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'news.categorys.flash.create.success');
            return $this->redirect($this->generateUrl('avl_news_categorys', array('groupId' => $entity->getId())));
        }

        return $this->render('UserBundle:News:edit.categorys.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @param $groupId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($groupId)
    {
        $entity = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:NewsCategorys')
            ->find($groupId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsCategorys entity.');
        }

        return $this->render('UserBundle:News:show.categorys.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * @param Request $request
     * @param $groupId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $groupId)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_SUBUSER_MANAGER'));

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UserBundle:NewsCategorys')->find($groupId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsCategorys entity.');
        }

        $form = $this->createForm(new NewsCategorysType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'news.categorys.flash.edit.success');
            return $this->redirect($this->generateUrl('avl_news_categorys', array('groupId' => $groupId)));
        }

        return $this->render('UserBundle:News:edit.categorys.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $groupId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $groupId)
    {
        if ($request->getMethod() == 'DELETE') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:NewsCategorys')->find($groupId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NewsCategorys entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'news.categorys.flash.remove.success');
        }

        return $this->redirect($this->generateUrl('avl_news_categorys'));
    }
}
