<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Entity\Faq;
use Avl\UserBundle\Form\Type\FaqType;
use Avl\UserBundle\Form\Type\SearchFormType;
use Symfony\Component\HttpFoundation\Request;
use Avl\UserBundle\Controller\Controller as BaseController;

/**
 * Faq controller.
 */
class FaqController extends BaseController
{
    /**
     * Faq-Repository
     */
    const FAQ_REPOSITORY = 'UserBundle:Faq';

    /**
     * Faq-Categorys-Repository
     */
    const FAQ_CATEGORYS_REPOSITORY = 'UserBundle:FaqCategorys';

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN'));

        $form = $this->createForm(new SearchFormType());
        $form->submit($request);

        $query = $this->getEm()
            ->getRepository(self::FAQ_REPOSITORY)
            ->getAllFaqByQuery(
                $form->getData()
            );

        $entities = $this->get('knp_paginator')
            ->paginate($query, $request->query->get('page', 1), 10);

        return $this->render('UserBundle:Faq:list.faq.html.twig', array(
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

        $entity = new Faq($this->getUser());
        $form = $form = $this->createForm(new FaqType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->persist($entity);
            $this->getEm()->flush();

            $this->get('session')->getFlashBag()->add('notice', 'faq.flash.create.success');
            return $this->redirect($this->generateUrl('avl_faq'));
        }
        return $this->render('UserBundle:Faq:edit.faq.html.twig', array(
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
        $entity = $this->getAllFaqWhereEnabledAndInternalBySlug($slug);
        if (!$entity) {
            $this->get('session')->getFlashBag()->add('error', 'error.notfound');
            return $this->redirect($this->generateUrl('avl_user_dashboard_show'));
        }
        return $this->render('UserBundle:Faq:show.faq.html.twig', array(
            'entity' => $entity,
            'entityCategorys' => $this->getFaqCategory(),
            'slug' => $entity->getCategory()->getPath()
        ));
    }

    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoryAction(Request $request, $slug = null)
    {
        $form = $this->createForm(new SearchFormType());
        $form->submit($request);

        $query = $this->getEm()
            ->getRepository(self::FAQ_REPOSITORY)
            ->getAllInternalFaqFromCategorysBySlug(
                $slug, $form->getData()
            );

        $entityCategory = $this->get('knp_paginator')
            ->paginate($query, $request->query->get('page', 1), 10);

        if (!$entityCategory) {
            $this->get('session')->getFlashBag()->add('error', 'error.notfound');
            return $this->redirect($this->generateUrl('avl_user_dashboard_show'));
        }

        return $this->render('UserBundle:Faq:show.category.html.twig', array(
            'entityCategory' => $entityCategory,
            'category' => (!empty($slug)) ? $this->getFaqCategoryBySlug($slug) : array(),
            'entityCategorys' => $this->getFaqCategory(),
            'form' => $form->createView(),
            'slug' => $slug
        ));
    }

    /**
     * @param Request $request
     * @param $faqId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $faqId)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN'));
        $entity = $this->getEm()->getRepository(self::FAQ_REPOSITORY)->find($faqId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }
        if (null === $entity->getUser()) {
            $entity->setUser($this->getUser());
        }
        $form = $this->createForm(new FaqType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'faq.flash.edit.success');
            return $this->redirect($this->generateUrl('avl_faq'));
        }
        return $this->render('UserBundle:Faq:edit.faq.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $faqId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $faqId)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN'));

        if ($request->getMethod() == 'DELETE') {
            $entity = $this->getEm()->getRepository(self::FAQ_REPOSITORY)->find($faqId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Faq entity.');
            }
            $this->getEm()->remove($entity);
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'faq.flash.remove.success');
        }
        return $this->redirect($this->generateUrl('avl_faq'));
    }

    /**
     * @param $slug
     * @return mixed
     */
    private function getFaqCategoryBySlug($slug)
    {
        return $this
            ->getEm()
            ->getRepository(self::FAQ_CATEGORYS_REPOSITORY)
            ->findOneByPath($slug);
    }

    /**
     * @param $slug
     */
    private function getAllFaqWhereEnabledAndInternalBySlug($slug)
    {
        return $this
            ->getEm()
            ->getRepository(self::FAQ_REPOSITORY)
            ->getAllFaqWhereEnabledAndInternalBySlug($slug);
    }
}
