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

/**
 * Class ProfileEditListener
 * @package Avl\UserBundle\EventListener
 */
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
            FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onProfileEditSuccess',
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
    public function onProfileEditSuccess(FormEvent $event)
    {
        // nothing implemented yet
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onProfileCompleted(UserEvent $userEvent)
    {
        $this->setUsernameAndProfilePicturePath($userEvent);

        /**
         * If no error-message was set (etc. cropping picture)
         */
        if (!$this->session->getFlashBag()->has('error')) {
            $this->session->getFlashBag()->add('notice', 'Your data was edit.');
        }
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