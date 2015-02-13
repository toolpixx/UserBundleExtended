<?php

namespace Avl\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContentController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($slug)
    {
        try {
            return $this->render('UserBundle:Content:'.$slug.'.html.twig', array());
        } catch(\Exception $e) {
            return $this->render('UserBundle:Content:404.html.twig', array());
            //throw new NotFoundHttpException(
            //    $this->get('translator')->trans('404.not.found')
            //);
        }
    }
}