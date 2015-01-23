<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 12.01.15
 * Time: 16:40
 */
namespace Avl\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProfileEditListener
 * @package Avl\UserBundle\EventListener
 */
class ChangePasswordListener implements EventSubscriberInterface
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
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::PROFILE_EDIT_INITIALIZE => 'onChangePasswordInitialize',
            FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onChangePasswordSuccess',
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'onChangePasswordCompleted'
        );
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onChangePasswordInitialize(UserEvent $userEvent)
    {
        // nothing implemented yes
    }

    /**
     * @param FormEvent $event
     */
    public function onChangePasswordSuccess(FormEvent $event)
    {
        // nothing implemented yet
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onChangePasswordCompleted(UserEvent $userEvent)
    {
        $this->session->getFlashBag()->add('notice', 'Your data was edit.');
    }
}