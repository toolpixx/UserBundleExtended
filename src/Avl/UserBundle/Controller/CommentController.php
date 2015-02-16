<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Controller\Controller as BaseController;

/**
 * Class CommentController
 * @package Avl\UserBundle\Controller
 */
class CommentController extends BaseController {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Has user granted role?
        $this->hasGranted('ROLE_CUSTOMER_COMMENT_MANAGER');

        return $this->render('UserBundle:Comment:index.html.twig', array());
    }
}