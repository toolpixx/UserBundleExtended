<?php

namespace Avl\UserBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\ContextErrorException;

class DashboardController extends Controller
{
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /**
         * Log info to test chromephp
         */
        $this->get('logger')->info($this->getUser());

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
        try {
            return simplexml_load_file($url);
        } catch(ContextErrorException $e) {
            $this->get('logger')->error('Can not load: '.$url);
            $this->get('logger')->error($e->getCode().' : '.$e->getMessage());
        }
    }
}