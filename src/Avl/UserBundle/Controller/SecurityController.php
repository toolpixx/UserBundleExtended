<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;

/**
 * Class SecurityController
 * @package Avl\UserBundle\Controller
 */
class SecurityController extends BaseSecurityController {

    /**
     * Overriding login to add custom logic.
     *
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request) {

        // Check if user is loggin. If yes they cannot
        // login, too; he stilled login in past....
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return new RedirectResponse(
                $this->container->get('router')->generate('fos_ext_avl_user_dashboard_show',
                array())
            );
        }
        return parent::loginAction($request);
    }
}