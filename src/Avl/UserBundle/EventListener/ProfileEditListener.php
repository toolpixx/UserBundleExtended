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
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        // Setup username and profile-picture to the session
        $this->setUsernameAndProfilePicturePath($userEvent);
    }

    /**
     * @param FormEvent $event
     */
    public function onProfileEditSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();

        // Use the Crop-Service
        try {
            // Was profilePicture uploaded?
            if ($user->hasProfilePictureUpload()) {
                $this->cropImage($user);
            }
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $e->getMessage());
        }
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onProfileCompleted(UserEvent $userEvent)
    {
        // Setup username and profile-picture to the session
        $this->setUsernameAndProfilePicturePath($userEvent);

        // If no error-message was set (etc. cropping picture)
        if (!$this->session->getFlashBag()->has('error')) {
            $this->session->getFlashBag()->add('notice', 'profile.flash.updated');
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
    private function setUsernameAndProfilePicturePath(UserEvent $userEvent)
    {
        $this->container
            ->get('set_session_service')
            ->setUser(
                $userEvent->getUser()
            );
    }

    /**
     * @return mixed
     */
    private function cropImage($user)
    {
        // Get the cropimage-service
        $imageService = $this->container->get('image_service');

        // Setup the cropImage-Service
        $imageService->setImageCropY($user->getImageCropY());
        $imageService->setImageCropX($user->getImageCropX());
        $imageService->setImageCropHeight($user->getImageCropHeight());
        $imageService->setImageCropWidth($user->getImageCropWidth());
        $imageService->setImagePath($user->getProfilePictureFile()->getPathname());

        return $imageService->cropImage();
    }
}
