<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 12.01.15
 * Time: 16:40
 */
namespace Avl\UserBundle\EventListener;

use Avl\UserBundle\Entity\User;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class LoginListener
 * @package Avl\UserBundle\EventListener
 */
class LoginListener implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router) 
    {
        $this->router = $router;
        $this->session = new Session();
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents() 
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onImplicitLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        );
    }

    /**
     * @param UserEvent $event
     */
    public function onImplicitLogin(UserEvent $event) 
    {
        // nothing implemented yet
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) 
    {
        // Get user-object
        $user = $event->getAuthenticationToken()->getUser();

        // If user-object is instance of User
        // set the actually name into session.
        // (for output and so on...)
        if ($user instanceof User) {
            $this->session->set('username', $user->getUsername());
            $this->session->set('profilePicturePath', $user->getProfilePicturePath());
            $this->session->set('_locale', $user->getLocale());
        }
    }
}