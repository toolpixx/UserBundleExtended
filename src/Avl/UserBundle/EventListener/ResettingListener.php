<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avl\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ResettingListener
 * @package Avl\UserBundle\EventListener
 */
class ResettingListener implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
        $this->session = new Session();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_RESET_INITIALIZE => 'onResettingResetInitialize',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResettingResetSuccess'
        );
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onResettingResetInitialize(GetResponseUserEvent $event)
    {
        // Not use yet
    }

    /**
     * @param FormEvent $event
     */
    public function onResettingResetSuccess(FormEvent $event)
    {
        /**
         * @var $user \FOS\UserBundle\Model\UserInterface
         */
        $user = $event->getForm()->getData();

        $this->container
            ->get('set_session_service')
            ->setUser(
                $user
            );

        $event->setResponse(
            new RedirectResponse(
                $this->router->generate('avl_user_dashboard_show')
            )
        );
    }
}
