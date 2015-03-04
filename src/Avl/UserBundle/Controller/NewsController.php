<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Entity\News;
use Avl\UserBundle\Form\Type\NewsType;
use Avl\UserBundle\Form\Type\SubUserSearchFormType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Avl\UserBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * News controller.
 */
class NewsController extends BaseController
{
    /**
     * News-Repository
     */
    const NEWS_REPOSITORY = 'UserBundle:News';

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
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_SUBUSER_MANAGER'));

        $form = $this->createForm(new SubUserSearchFormType());
        $form->submit($request);

        $query = $this->getEm()
            ->getRepository(self::NEWS_REPOSITORY)
            ->getAllNewsByQuery(
                $form->getData()
            );

        $entities = $this->get('knp_paginator')
            ->paginate($query, $request->query->get('page', 1), 10);

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
            $this->getEm()->persist($entity);
            $this->getEm()->flush();

            $this->get('session')->getFlashBag()->add('notice', 'news.flash.create.success');
            return $this->redirect($this->generateUrl('avl_news', array('newsId' => $entity->getId())));
        }
        return $this->render('UserBundle:News:edit.news.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $entity = $this->getAllNewsWhereEnabledAndInternalBySlug($slug);
        if (!$entity) {
            $this->get('session')->getFlashBag()->add('error', 'error.notfound');
            return $this->redirect($this->generateUrl('avl_user_dashboard_show'));
        }
        return $this->render('UserBundle:News:show.news.html.twig', array(
            'entity' => $entity,
            'entityCategorys' => $this->getNewsCategory()
        ));
    }

    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoryAction($slug = '')
    {
        $entityCategory = $this->getAllInternalNewsFromCategoryBySlug($slug);
        if (!$entityCategory) {
            $this->get('session')->getFlashBag()->add('error', 'error.notfound');
            return $this->redirect($this->generateUrl('avl_user_dashboard_show'));
        }
        return $this->render('UserBundle:News:show.category.html.twig', array(
            'entityCategory' => $this->getAllInternalNewsFromCategoryBySlug($slug),
            'category' => (!empty($slug)) ? $this->getNewsCategoryBySlug($slug) : array(),
            'entityCategorys' => $this->getNewsCategory()
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
        $entity = $this->getEm()->getRepository(self::NEWS_REPOSITORY)->find($newsId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }
        if (null === $entity->getUser()) {
            $entity->setUser($this->getUser());
        }
        $form = $this->createForm(new NewsType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'news.flash.edit.success');
            return $this->redirect($this->generateUrl('avl_news'));
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
            $entity = $this->getEm()->getRepository(self::NEWS_REPOSITORY)->find($newsId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find News entity.');
            }
            $this->getEm()->remove($entity);
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'news.flash.remove.success');
        }
        return $this->redirect($this->generateUrl('avl_news'));
    }

    /**
     * @param $slug
     * @return mixed
     */
    private function getNewsCategoryBySlug($slug)
    {
        return $this
            ->getEm()
            ->getRepository(self::NEWS_CATEGORYS_REPOSITORY)
            ->findOneByPath($slug);
    }

    /**
     * @param $slug
     * @return mixed
     */
    private function getAllInternalNewsFromCategoryBySlug($slug)
    {
        return $this
            ->getEm()
            ->getRepository(self::NEWS_REPOSITORY)
            ->getAllInternalNewsFromCategorysBySlug($slug);
    }

    /**
     * @param $slug
     */
    private function getAllNewsWhereEnabledAndInternalBySlug($slug)
    {
        return $this
            ->getEm()
            ->getRepository(self::NEWS_REPOSITORY)
            ->getAllNewsWhereEnabledAndInternalBySlug($slug);
    }
}
