<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use FOS\UserBundle\Controller\ProfileController as BaseProfileController;

/**
 * Class ProfileController
 * @package Avl\UserBundle\Controller
 */
class ProfileController extends BaseProfileController
{
    /**
     * @var null
     */
    private $session = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * Overriding profile edit to add custom logic.
     *
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        // nothing implemented yet
        return parent::editAction($request);
    }

    /**
     * Delete profile-picture
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function deletePictureAction(Request $request) {

        /**
         * Method DELETE?
         */
        if ($request->getMethod() == 'DELETE') {

            // Can i delete the picture?
            if ($this->getUser()->removeProfilePictureFile()) {

                // Update user profile
                $this->get('fos_user.user_manager')->updateUser(
                    $this->getUser()
                );

                $this->session->getFlashBag()->add('notice', 'Picture was deleted.');
            } else {
                $this->session->getFlashBag()->add('notice', 'Cannot delete picture.');
            }
        }

        return new RedirectResponse(
            $this->container->get('router')->generate('fos_user_profile_edit',
                array())
        );
    }
}