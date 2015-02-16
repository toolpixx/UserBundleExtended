<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Controller\Controller as BaseController;

class EventController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Has user granted role?
        $this->hasGranted('ROLE_CUSTOMER_EVENT_MANAGER');

        return $this->render('UserBundle:Event:index.html.twig', array());
    }
}