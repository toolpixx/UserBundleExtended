<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Exception\ContextErrorException;

/**
 * Class DashboardController
 * @package Avl\UserBundle\Controller
 */
class DashboardController extends BaseController
{
    /**
     * Url for the rss from symfony.com
     */
    const SYMFONY_RSS_URL = 'http://feeds.feedburner.com/symfony/blog';

    /**
     * @var null|object
     */
    private $cacheDriver = null;

    /**
     * @var null
     */
    private $cacheKey = null;

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        // Log info to test chromephp
        $this->get('logger')->info($this->getUser());

        // Can i view the subuser?
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CUSTOMER_SUBUSER_MANAGER')) {
            $pagination = $this->getUserPagination($request, null, 5);
        } else {
            $pagination = null;
        }

        return $this->render(
            'UserBundle:Dashboard:index.html.twig',
            array(
                'user' => $this->getUser(),
                'entities' => $pagination,
                'symfonyRss' => $this->getRssFeed(self::SYMFONY_RSS_URL)
            )
        );
    }

    /**
     * Get a rssfeed
     *
     * @param  $url
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
            } else {
                // Or get and save the new content
                $rssFeed = file_get_contents($url);

                // Save to cache ad return content
                $this->cacheDriver->save($this->cacheKey, $rssFeed, 3600 * 24);

                return simplexml_load_string($rssFeed);
            }

        } catch (ContextErrorException $e) {
            $this->get('logger')->error($e->getCode() . ' : ' . $e->getMessage());
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
