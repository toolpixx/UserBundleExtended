<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Entity\FaqCategorys;
use Avl\UserBundle\Form\Type\FaqCategorysType;
use Avl\UserBundle\Form\Type\SearchFormType;
use Symfony\Component\HttpFoundation\Request;
use Avl\UserBundle\Controller\Controller as BaseController;

/**
 * Faq controller.
 */
class FaqCategorysController extends BaseController
{
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

        $query = $this
            ->getEm()
            ->getRepository(self::FAQ_CATEGORYS_REPOSITORY)
            ->getAllFaqCategorysByQuery($form->getData());

        $entities = $this->get('knp_paginator')
            ->paginate($query, $request->query->get('page', 1), 10);

        return $this->render('UserBundle:Faq:list.categorys.html.twig', array(
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

        $entity = new FaqCategorys();
        $form = $this->createForm(new FaqCategorysType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->persist($entity);
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'faq.categorys.flash.create.success');
            return $this->redirect($this->generateUrl('avl_faq_categorys'));
        }
        return $this->render('UserBundle:Faq:edit.categorys.html.twig', array(
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
            ->getRepository(self::FAQ_CATEGORYS_REPOSITORY)
            ->find($groupId);

        if (!$entity) {
            $this->entityNotFound();
        }
        return $this->render('UserBundle:Faq:show.categorys.html.twig', array(
            'entity' => $entity,
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

        $entity = $this->getEm()->getRepository(self::FAQ_CATEGORYS_REPOSITORY)->find($groupId);
        if (!$entity) {
            $this->entityNotFound();
        }
        $form = $this->createForm(new FaqCategorysType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'faq.categorys.flash.edit.success');
            return $this->redirect($this->generateUrl('avl_faq_categorys'));
        }
        return $this->render('UserBundle:Faq:edit.categorys.html.twig', array(
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
            $entity = $this->getEm()->getRepository(self::FAQ_CATEGORYS_REPOSITORY)->find($groupId);
            if (!$entity) {
                $this->entityNotFound();
            }

            $this->getEm()->remove($entity);
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'faq.categorys.flash.remove.success');
        }
        return $this->redirect($this->generateUrl('avl_faq_categorys'));
    }

    /**
     * @param string $message
     */
    private function entityNotFound($message = '')
    {
        throw $this->createNotFoundException((empty($message)) ? 'Unable to find FaqCategorys entity.' : $message);
    }
}
