<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Entity\NewsCategorys;
use Avl\UserBundle\Form\Type\NewsCategorysType;
use Avl\UserBundle\Form\Type\SubUserSearchFormType;
use Symfony\Component\HttpFoundation\Request;
use Avl\UserBundle\Controller\Controller as BaseController;

/**
 * News controller.
 */
class NewsCategorysController extends BaseController
{
    /**
     * News-Categorys-Repository
     */
    const NEWS_CATEGORYS_REPOSITORY = 'UserBundle:NewsCategorys';

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN'));

        $form = $this->createForm(new SubUserSearchFormType());
        $form->submit($request);

        $query = $this
            ->getEm()
            ->getRepository(self::NEWS_CATEGORYS_REPOSITORY)
            ->getAllNewsCategorysByQuery($form->getData());

        $entities = $this->get('knp_paginator')
            ->paginate($query, $request->query->get('page', 1), 10);

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
        $this->hasGranted(array('ROLE_ADMIN'));

        $entity = new NewsCategorys();
        $form = $this->createForm(new NewsCategorysType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->persist($entity);
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'news.categorys.flash.create.success');
            return $this->redirect($this->generateUrl('avl_news_categorys'));
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
            ->getEm()
            ->getRepository(self::NEWS_CATEGORYS_REPOSITORY)
            ->find($groupId);

        if (!$entity) {
            $this->entityNotFound();
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
        $this->hasGranted(array('ROLE_ADMIN'));

        $entity = $this->getEm()->getRepository(self::NEWS_CATEGORYS_REPOSITORY)->find($groupId);
        if (!$entity) {
            $this->entityNotFound();
        }
        $form = $this->createForm(new NewsCategorysType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'news.categorys.flash.edit.success');
            return $this->redirect($this->generateUrl('avl_news_categorys'));
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
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN'));

        if ($request->getMethod() == 'DELETE') {
            $entity = $this->getEm()->getRepository(self::NEWS_CATEGORYS_REPOSITORY)->find($groupId);
            if (!$entity) {
                $this->entityNotFound();
            }

            $this->getEm()->remove($entity);
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'news.categorys.flash.remove.success');
        }
        return $this->redirect($this->generateUrl('avl_news_categorys'));
    }

    /**
     * @param string $message
     */
    private function entityNotFound($message = '')
    {
        throw $this->createNotFoundException((empty($message)) ? 'Unable to find NewsCategorys entity.' : $message);
    }
}
