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
        return $this->render(
            'UserBundle:Dashboard:index.html.twig',
            array(
                'user' => $this->getUser(),
                'entities' => $this->getSubuser($request),
                'news' => $this->getNews(),
                'entityCategorys' => $this->getNewsCategorys(),
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
            $this->cacheKey = $url;
            return $this->getCachedFeed();

        } catch (ContextErrorException $e) {
            $this->get('logger')->error($e->getCode() . ' : ' . $e->getMessage());
        } finally {
            return $this->loadCachedFeed();
        }
    }

    /**
     * @return \SimpleXMLElement
     */
    private function getCachedFeed()
    {
        $this->cacheDriver = $this->container->get('liip_doctrine_cache.ns.rssfeed');

        if ($this->cacheDriver->contains($this->cacheKey)) {
            return $this->loadCachedFeed();
        }

        $rssFeed = file_get_contents($this->cacheKey);
        $this->cacheDriver->save($this->cacheKey, $rssFeed, 3600 * 24);
        return simplexml_load_string($rssFeed);
    }

    /**
     * Get the content from cache...
     *
     * @return \SimpleXMLElement
     */
    private function loadCachedFeed()
    {
        return
            simplexml_load_string(
                $this->cacheDriver->fetch($this->cacheKey)
            );
    }

    /**
     * @return mixed|null
     */
    private function getSubuser($request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CUSTOMER_SUBUSER_MANAGER')) {
            return $this->getUserPagination($request, null, 5);
        }

        return null;
    }

    /**
     * @return mixed
     */
    private function getNews()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:News')
            ->findBy(
                array(
                    'enabled' => true,
                    'internal' => true
                ),
                array('createdDate' => 'DESC')
            );
    }

    private function getNewsCategorys()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('UserBundle:NewsCategorys')
            ->findBy(
                array(
                    'enabled' => true,
                    'internal' => true
                ),
                array('name' => 'ASC')
            );
    }
}
