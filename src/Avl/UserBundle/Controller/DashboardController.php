<?php

namespace Avl\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;

class DashboardController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('UserBundle:Dashboard:index.html.twig', array(
            'user' => $this->getUser(),
            'symfonyRss' => $this->getRssFeed('http://feeds.feedburner.com/symfony/blog'),
            'stackoverflowRss' => $this->getRssFeed('http://stackoverflow.com/feeds/tag/php+symfony'),
            'githubRss' => $this->getRssFeed('https://github.com/symfony/symfony/commits/master.atom'),
        ));
    }

    /**
     * Get a rssfeed
     *
     * Todo: make it better (errorhandling)
     *
     * @param $url
     * @return \SimpleXMLElement
     */
    private function getRssFeed($url) {

        return
            simplexml_load_file($url);
    }
}