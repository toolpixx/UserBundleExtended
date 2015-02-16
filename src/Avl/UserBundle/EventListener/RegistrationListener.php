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
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RegistrationListener
 * @package Avl\UserBundle\EventListener
 */
class RegistrationListener implements EventSubscriberInterface {

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
     * @param ContainerInterface $container
     */
    public function __construct(UrlGeneratorInterface $router, ContainerInterface $container) {

        $this->router = $router;
        $this->container = $container;
        $this->session = new Session();
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents() {

        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationConfirmed'
        );
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event) {

        // Get the userdate
        $user = $event->getForm()->getData();

        // Add some roles to the user
        $user->setRoles(
            array_keys(
                $user->getUsedRoles()
            )
        );
    }

    /**
     * @param FilterUserResponseEvent $responseEvent
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $responseEvent) {

        // Get the user who created
        $user = $responseEvent->getUser();

        // Insert parentId for the user
        $userManager = $this->container->get('fos_user.user_manager');

        // Set the parentId of his own id
        $user->setParentId($user->getId());

        // Update the user
        $userManager->updateUser($user);
    }

    /**
     * @param FilterUserResponseEvent $responseEvent
     */
    public function onRegistrationConfirmed(FilterUserResponseEvent $responseEvent) {
        $this->session->set('username', $responseEvent->getUser()->getUsername());
        $this->session->set('_locale', $responseEvent->getUser()->getLocale());
    }
}