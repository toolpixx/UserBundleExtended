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
     * This method checks if user is granted
     * if not it throw exception, otherwise
     * return true.
     *
     * @param $roles
     * @return bool
     */
    public function hasGranted($roles)
    {
        if (!$this->checkGrantedRoles($roles)) {
            throw $this->createAccessDeniedException();
        }
        return true;
    }

    /**
     * This method checks if user is granted
     * if not it return false, otherwise
     * return true.
     *
     * @param $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        return $this->checkGrantedRoles($roles);
    }

    /**
     * Pagiation for the subuser-management
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
            ->getAllSubUserByParentId(
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

    /**
     * Pagiation for the subuser-management
     * @param $request
     * @param $formData
     * @param $resultsPerSite
     * @return mixed
     */
    public function getAdminUserPagination($request, $formData, $resultsPerSite)
    {
        $query = $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:User')
            ->getAllSubUser(
                $this->getUser()->getId(),
                $formData
            );

        return $this->get('knp_paginator')
            ->paginate(
                $query,
                $request->query->get('page', 1),
                $resultsPerSite
            );
    }

    /**
     * Checks if a role is granted. $roles
     * can be an array or string
     *
     * @param $roles
     * @return bool
     */
    private function checkGrantedRoles($roles)
    {
        $checkSecurity = array();
        // If $roles is array, iterate it.
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if (in_array($this->get('security.authorization_checker')->isGranted($role), $roles)) {
                    $checkSecurity[] = true;
                }
            }
        } else {
            // $role is only a string
            if ($this->get('security.authorization_checker')->isGranted($roles)) {
                $checkSecurity[] = true;
            }
        }
        return (count($checkSecurity) > 0) ? true : false;
    }
}
