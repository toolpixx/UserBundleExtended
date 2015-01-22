<?php

namespace Avl\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('UserBundle:Dashboard:index.html.twig', array(
            'user' => $this->getUser(),
            'symfonyRss' => \Feed::loadAtom('http://feeds.feedburner.com/symfony/blog')->toArray(),
            //'stackoverflowRss' => \Feed::loadAtom('http://stackoverflow.com/feeds/tag/php+symfony')->toArray(),
            //'githubRss' => \Feed::loadAtom('https://github.com/toolpixx/FOSUserBundleExtended/commits/master.atom')->toArray(),
        ));
    }
}