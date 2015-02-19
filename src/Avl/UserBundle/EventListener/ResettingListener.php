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

class ResettingListener implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var Session
     */
    private $session;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
        $this->session = new Session();
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_RESET_INITIALIZE => 'onResettingResetInitialize',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResettingResetSuccess'
        );
    }

    public function onResettingResetInitialize(GetResponseUserEvent $event)
    {
        // Not use yet
    }

    public function onResettingResetSuccess(FormEvent $event)
    {
        // Setup username and profile-picture to the session
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();

        $this->session->set('username', $user->getUsername());
        $this->session->set('profilePicturePath', $user->getProfilePicturePath());
        $this->session->set('_locale', $user->getLocale());

        $event->setResponse(
            new RedirectResponse(
                $this->router->generate('fos_ext_avl_user_dashboard_show')
            )
        );
    }
}
