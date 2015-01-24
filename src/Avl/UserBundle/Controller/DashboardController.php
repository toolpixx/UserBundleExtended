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
            'scoopRss' => $this->getRssFeed('http://www.scoop.it/t/webdevilopers/rss.xml'),
            'githubRss' => $this->getRssFeed('https://github.com/symfony/symfony/commits/master.atom'),
        ));
    }

    /**
     * Get a rssfeed
     *
     * Todo: make it better (errorhandling and caching)
     *
     * @param $url
     * @return \SimpleXMLElement
     */
    private function getRssFeed($url) {
        try {
            /**
             * CacheKey
             */
            $cache_key = $url;

            /**
             * Get the cache-driver
             */
            $cache_driver = $this->container->get('liip_doctrine_cache.ns.rssfeed');

            /**
             * If content was cached return it
             */
            if ($cache_driver->contains($cache_key)) {

                return
                    simplexml_load_string($cache_driver->fetch($cache_key));
            }
            /**
             * Or get and save the new content
             */
            else {
                $rssFeed = file_get_contents($url);
                /**
                 * Save to cache ad return content
                 */
                $cache_driver->save($cache_key, $rssFeed, strtotime('+5 Minutes'));
                return simplexml_load_string($rssFeed);
            }

        } catch(ContextErrorException $e) {
            $this->get('logger')->error('Can not load: '.$url);
            $this->get('logger')->error($e->getCode().' : '.$e->getMessage());
        }
    }
}