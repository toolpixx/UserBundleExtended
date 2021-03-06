<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 12.01.15
 * Time: 16:40
 */
namespace Avl\UserBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContext;

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
     * @param ContainerInterface $container
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
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordSuccess',
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'onChangePasswordCompleted'
        );
    }

    /**
     * @param FormEvent $formEvent
     */
    public function onChangePasswordSuccess(FormEvent $formEvent)
    {
        // Redirect to dashboard after update
        $url = $this->router->generate('fos_user_change_password');
        $formEvent->setResponse(new RedirectResponse($url));
    }

    /**
     * onChangePasswordCompleted
     */
    public function onChangePasswordCompleted()
    {
        if (!$this->session->getFlashBag()->has('error')) {
            $this->session->getFlashBag()->add('notice', 'change_password.flash.success');
        }
    }
}
