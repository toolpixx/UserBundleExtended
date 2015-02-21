<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\ChangePasswordController as BaseChangePasswordController;

/**
 * Class ChangePasswordController
 * @package Avl\UserBundle\Controller
 */
class ChangePasswordController extends BaseChangePasswordController
{
    /**
     * Change user password
     *
     * @param  Request $request
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction(Request $request)
    {
        return parent::changePasswordAction($request);
    }
}
