<?php

namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Controller\Controller as BaseController;

use Symfony\Component\Finder\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SubUserController extends BaseController
{
    /**
     * \FOS\UserBundle\Model\UserManagerInterface
     */
    const USER_MANAGER = 'fos_user.user_manager';

    /**
     * For the profile form
     *
     * \FOS\UserBundle\Form\Factory\FactoryInterface
     */
    const FORM_FACTORY_PROFILE = 'avl_user.profile.form.factory';

    /**
     * For the registration form
     *
     * \FOS\UserBundle\Form\Factory\FactoryInterface
     */
    const FORM_FACTORY_REGISTRATION = 'fos_user.registration.form.factory';

    /**
     * @var Session
     */
    private $session;

    /**
     * Contructor
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Has user granted role?
        $this->hasGranted('ROLE_CUSTOMER_SUBUSER_MANAGER');

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('UserBundle:User')->findAllSubUserByParentId($this->getUser()->getId(), $this->getUser()->getParentId());

        return $this->render('UserBundle:SubUser:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * New subuser
     *
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        // Has user granted role?
        $this->hasGranted('ROLE_CUSTOMER_SUBUSER_MANAGER');

        $user = $this->getUserManager()->createUser();
        $user->setEnabled(true);

        $form = $this->getFormFactory(self::FORM_FACTORY_REGISTRATION)->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $user->setParentId(
                $this->getUser()->getParentId()
            );
            $this->getUserManager()->updateUser($user);

            return $this->redirectSubUser('Subuser was created');
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
            'path' => 'avl_subuser_new'
        ));
    }

    /**
     * Edit subuser
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        // Has user granted role?
        $this->hasGranted('ROLE_CUSTOMER_SUBUSER_MANAGER');

        // Find the user to edit
        $user = $this->findUser($id);

        // Add Data and the request to the form
        $formFactory = $this->getFormFactory(self::FORM_FACTORY_PROFILE);

        // Add the user to the formobject
        $formFactory->setUser($user);

        // Create the form
        $form = $formFactory->createForm();

        // Add Data and the request to the form
        $form->setData($user);
        $form->handleRequest($request);

        // If method was POST and is valid,
        // then we save and redirect the
        // output.
        if ($request->getMethod() == 'POST' && $form->isValid()) {

            // Update the user
            $this->getUserManager()->updateUser($user);

            // Crop image
            if ($this->cropImage($user)) {
                return $this->redirectSubUser('Subuser was editing');
            }
        }
        // Get and create the FOSUserbundleForm
        // Render my view with additional data
        return $this->render('UserBundle:Profile:edit.html.twig',
            array(
                'id' => $id,
                'form' => $form->createView(),
                'path' => 'avl_subuser_edit',
                'profilePicturePath' => $user->getProfilePicturePath()
            )
        );
    }

    /**
     * Remove subuser
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function removeAction(Request $request, $id)
    {
        // Has user granted role?
        $this->hasGranted('ROLE_CUSTOMER_SUBUSER_MANAGER');

        if ($request->getMethod() == 'DELETE') {
            try {
                $em = $this->getDoctrine()->getManager();
                // Find the user to delete
                $user = $this->findUser($id);

                if (null !== $user && is_object($user)) {
                    $em->remove($user);
                    $em->flush();
                    return $this->redirectSubUser('Subuser was removed');
                } else {
                    throw new AccessDeniedException('This user does not have access to this section.');
                }
            } catch(AccessDeniedException $e) {
                $this->session->getFlashBag()->add('error', 'Subuser cannot be removed');
                return $this->redirectSubUser();
            }
        } else {
            return $this->redirectSubUser();
        }
    }

    /**
     * Find the correct user by
     * id and parentId
     *
     * @param $id
     * @return \FOS\UserBundle\Model\UserInterface
     */
    private function findUser($id) {

        // Get the userManager
        $userManager = $this->getUserManager();

        return $userManager->findUserBy(
            array(
                'id' => (integer) $id,
                'parentId' => (integer) $this->getUser()->getParentId()
            )
        );
    }

    /**
     * Get the userManager from FOSUserBundle
     *
     * @return object
     */
    private function getUserManager() {
        return $this->get(self::USER_MANAGER);
    }

    /**
     * Get the formFactory from FOSUserBundle
     *
     * @param null $formFactory
     * @return null|object
     */
    private function getFormFactory($formFactory = null) {
        return (null !== $formFactory) ? $this->get($formFactory) : null;
    }

    /**
     * Redirect to the subuseroverview
     *
     * @param null $message
     * @return RedirectResponse
     */
    private function redirectSubUser($message = null) {

        if (null !== $message && !$this->session->getFlashBag()->has('error')) {
            $this->session->getFlashBag()->add('notice', (string) $message);
        }
        return $this->redirect(
            $this->generateUrl('avl_subuser')
        );
    }

    /**
     * Crop the current user avatar
     *
     * @param $user
     * @return bool
     */
    private function cropImage($user) {

        try {
            if ($user->getImageCropY() != '') {
                // Get the cropimage-service
                $cropImage = $this->container->get('crop_image');

                // crop the image
                $cropImage->cropImage(
                    array(
                        'cropY' => $user->getImageCropY(),
                        'cropX' => $user->getImageCropX(),
                        'cropHeight' => $user->getImageCropHeight(),
                        'cropWidth' => $user->getImageCropWidth(),
                        'cropImagePath' => $user->getProfilePictureAbsolutePath()
                    )
                );
            }
            return true;
        } catch(Exception $e) {
            $this->session->getFlashBag()->add('error', $e->getMessage());
        }
        return false;
    }
}