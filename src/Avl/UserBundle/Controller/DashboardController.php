<?php

namespace Avl\UserBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\ContextErrorException;

class DashboardController extends Controller
{
    /**
     * @var null|object
     */
    private $cacheDriver = null;

    /**
     * @var null
     */
    private $cacheKey = null;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Log info to test chromephp
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
    private function getRssFeed($url)
    {
        try {
            // CacheKey
            $this->cacheKey = $url;

            // Get the cachedriver
            $this->cacheDriver = $this->container->get('liip_doctrine_cache.ns.rssfeed');

            // If content was cached return it
            if ($this->cacheDriver->contains($this->cacheKey)) {
                return $this->getCachedFeed();
            }
            // Or get and save the new content
            else {
                $rssFeed = file_get_contents($url);

                // Save to cache ad return content
                $this->cacheDriver->save($this->cacheKey, $rssFeed, 3600*24); // 1 hour

                return simplexml_load_string($rssFeed);
            }

        } catch(ContextErrorException $e) {
            $this->get('logger')->error('Can not load: '.$url);
            $this->get('logger')->error($e->getCode().' : '.$e->getMessage());
        } finally {
            return $this->getCachedFeed();
        }
    }

    /**
     * Get the content from cache...
     *
     * @return \SimpleXMLElement
     */
    private function getCachedFeed()
    {
        return
            simplexml_load_string(
                $this->cacheDriver->fetch($this->cacheKey)
            );
    }
}