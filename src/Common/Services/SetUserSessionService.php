<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 25.01.15
 * Time: 02:00
 */
namespace Common\Services;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class CropImage
 * @package Common
 */
class SetUserSessionService
{
    /**
     * @var
     */
    private $session;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->session->set('username', $user->getUsername());
        $this->session->set('profilePicturePath', $user->getProfilePicturePath());
        $this->session->set('_locale', $user->getLocale());
    }
}