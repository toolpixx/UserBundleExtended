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
     * News-Repository
     */
    const NEWS_REPOSITORY = 'UserBundle:News';

    /**
     * News-Categorys-Repository
     */
    const NEWS_CATEGORYS_REPOSITORY = 'UserBundle:NewsCategorys';

    /**
     * Faq-Categorys-Repository
     */
    const FAQ_CATEGORYS_REPOSITORY = 'UserBundle:FaqCategorys';

    /**
     * Security-Authorization-Checker
     */
    const SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * Doctrine-Orm-Entity-Manager
     */
    const DOCTRINE_ORM_ENTITY_MANAGER = 'doctrine.orm.entity_manager';

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
                if (in_array($this->get(self::SECURITY_AUTHORIZATION_CHECKER)->isGranted($role), $roles)) {
                    $checkSecurity[] = true;
                }
            }
        } else if ($this->get(self::SECURITY_AUTHORIZATION_CHECKER)->isGranted($roles)) {
            $checkSecurity[] = true;
        }
        return (count($checkSecurity) > 0) ? true : false;
    }

    /**
     * @return mixed
     */
    public function getNewsCategory()
    {
        return $this
            ->getEm()
            ->getRepository(self::NEWS_CATEGORYS_REPOSITORY)
            ->getAllNewsCategoryWhereNewsEnabledAndInternal();
    }

    /**
     * @return mixed
     */
    public function getFaqCategory()
    {
        return $this
            ->getEm()
            ->getRepository(self::FAQ_CATEGORYS_REPOSITORY)
            ->getAllFaqCategoryWhereFaqEnabledAndInternal();
    }

    /**
     * @return object
     */
    public function getEm()
    {
        return $this->container->get(self::DOCTRINE_ORM_ENTITY_MANAGER);
    }
}