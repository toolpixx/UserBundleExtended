<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Controller\Controller as BaseController;

class ContentController extends BaseController
{
    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($slug)
    {
        try {
            return $this->render('UserBundle:Content:'.$slug.'.html.twig', array());
        } catch(\Exception $e) {
            return $this->render('UserBundle:Content:404.html.twig', array());
        }
    }
}