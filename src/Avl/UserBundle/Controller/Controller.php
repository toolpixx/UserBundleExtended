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
     * Checks if a role is granted. $roles
     * can be an array or string
     *
     * @param $roles
     * @return bool
     */
    private function checkGrantedRoles($roles)
    {
        $checkSecurity = array();
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if (in_array($this->get('security.authorization_checker')->isGranted($role), $roles)) {
                    $checkSecurity[] = true;
                }
            }
        } else if ($this->get('security.authorization_checker')->isGranted($roles)) {
            $checkSecurity[] = true;
        }
        return (count($checkSecurity) > 0) ? true : false;
    }

    /**
     * @return mixed
     */
    public function getInternalNews()
    {
        return $this
            ->getEm()
            ->getRepository('UserBundle:News')
            ->findInteralEnabledNews();
        /**
            ->findBy(
                array(
                    'enabled' => true,
                    'internal' => true
                ),
                array('createdDate' => 'DESC')
            );
         **/
    }

    /**
     * @return mixed
     */
    public function getNewsCategory()
    {
        return $this
            ->getEm()
            ->getRepository('UserBundle:NewsCategorys')
            ->findBy(
                array(
                    'enabled' => true,
                    'internal' => true
                ),
                array('name' => 'ASC')
            );
    }

    /**
     * @return object
     */
    public function getEm()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }
}
