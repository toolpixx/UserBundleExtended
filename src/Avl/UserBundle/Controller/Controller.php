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
abstract class Controller extends BaseController {

    /**
     * @param $role
     * @return bool
     */
    public function hasGranted($role) {
        if (!$this->get('security.authorization_checker')->isGranted($role)) {
            throw $this->createAccessDeniedException();
        }
        return true;
    }
}