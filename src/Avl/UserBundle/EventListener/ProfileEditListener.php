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

class ProfileEditListener implements EventSubscriberInterface
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
            FOSUserEvents::PROFILE_EDIT_INITIALIZE => 'onProfileInitialize',
            FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onProfileEdit',
            FOSUserEvents::PROFILE_EDIT_COMPLETED => 'onProfileCompleted'
        );
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onProfileInitialize(UserEvent $userEvent)
    {
        $this->setUsernameAndProfilePicturePath($userEvent);
    }

    /**
     * @param FormEvent $event
     */
    public function onProfileEdit(FormEvent $event)
    {
        // nothing implemented yet
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onProfileCompleted(UserEvent $userEvent)
    {
        $this->setUsernameAndProfilePicturePath($userEvent);
    }

    /**
     * Set the username into session, because
     * if you change the name and error ocurred
     * sf will change the name to the new one.
     *
     * We want change the name only if form-data
     * is persist.
     *
     * @param UserEvent $userEvent
     */
    private function setUsernameAndProfilePicturePath(UserEvent $userEvent) {
        $this->session->set('username', $userEvent->getUser()->getUsername());
        $this->session->set('profilePicturePath', $userEvent->getUser()->getProfilePicturePath());
    }
}