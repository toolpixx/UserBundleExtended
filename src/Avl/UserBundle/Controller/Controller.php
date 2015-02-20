<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 16.02.15
 * Time: 16:45
 */
namespace Avl\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Class Controller
 * @package Avl\UserBundle\Controller
 */
abstract class Controller extends BaseController
{
    /**
     * @param $role
     * @return bool
     */
    public function hasGranted($role) 
    {
        if (!$this->get('security.authorization_checker')->isGranted($role)) {
            throw $this->createAccessDeniedException();
        }
        return true;
    }

    /**
     * Pagiation for the subuser-management
     *
     * @param $request
     * @param $formData
     * @param $resultsPerSite
     * @return mixed
     */
    public function getUserPagination($request, $formData, $resultsPerSite)
    {
        $query = $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:User')
            ->findAllSubUserByParentId(
                $this->getUser()->getId(),
                $this->getUser()->getParentId(),
                $formData
            );

        return $this->get('knp_paginator')
            ->paginate(
                $query,
                $request->query->get('page', 1),
                $resultsPerSite
            );
    }
}