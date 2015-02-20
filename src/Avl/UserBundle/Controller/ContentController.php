<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Controller\Controller as BaseController;

/**
 * Class ContentController
 * @package Avl\UserBundle\Controller
 */
class ContentController extends BaseController
{
    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($slug)
    {
        try {
            return $this->render(
                'UserBundle:Content:'.$slug.'.html.twig', array(
                'slug' => $slug
                )
            );
        } catch(\Exception $e) {
            return $this->render('UserBundle:Content:404.html.twig', array());
        }
    }
}