<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ResettingController as BaseResettingController;

/**
 * Class ResettingController
 * @package Avl\UserBundle\Controller
 */
class ResettingController extends BaseResettingController
{
    /**
     * Overriding resetting to add custom logic.
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function requestAction()
    {
        // Check if user is loggin. If yes they cannot
        // resetting passwort....
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->get('session')->getFlashBag()->add('notice', 'Please logout before resetting password.');

            return new RedirectResponse(
                $this->container->get('router')->generate(
                    'avl_user_dashboard_show',
                    array()
                )
            );
        }

        return parent::requestAction();
    }
}
