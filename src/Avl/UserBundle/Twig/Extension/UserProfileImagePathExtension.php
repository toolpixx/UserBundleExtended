<?php

namespace Avl\UserBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\Session\Session;
use Avl\UserBundle\Entity\User as User;

class UserProfileImagePathExtension extends \Twig_Extension
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Session
     */
    private $session;

    public function __construct()
    {
        $this->session = new Session();
        $this->user = new User();
    }

    public function getName()
    {
        return 'profile_image_extension';
    }

    public function getFunctions()
    {
        return array(
            'UserProfileImagePath' => new \Twig_Function_Method($this, 'UserProfileImagePath')
        );
    }

    public function UserProfileImagePath()
    {
        return true
            && null != $this->session->get('profilePicturePath')
            && is_file($this->user->getUploadRootDir().'/'.$this->session->get('profilePicturePath'))
            ? $this->user->getUploadDir().'/'.$this->session->get('profilePicturePath')
            : $this->user->getUploadDir().'/default-avatar.png';
    }
}
