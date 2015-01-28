<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 20.01.15
 * Time: 14:40
 */
namespace Avl\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class RegistrationListener
 * @package Avl\UserBundle\EventListener
 */
class RegistrationListener implements EventSubscriberInterface
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
     * Add more roles if you wan't. But do not forget
     * to define the roles in security.yaml
     *
     * @var array
     */
    private $roles = array('ROLE_CUSTOMER');

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
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        // Get the userdate
        $user = $event->getForm()->getData();

        // Add some roles to the user
        $user->setRoles(
            $this->roles
        );
    }

    /**
     * @param FilterUserResponseEvent $responseEvent
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $responseEvent)
    {
        // not implemented yet
    }
}