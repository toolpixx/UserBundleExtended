<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 16.02.15
 * Time: 16:45
 */
namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Controller\Controller as BaseController;
use Avl\UserBundle\Form\Type\SubUserSearchFormType;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class SubUserController
 * @package Avl\UserBundle\Controller
 */
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_SUBUSER_MANAGER'));

        $form = $this->createForm(new SubUserSearchFormType());
        $form->submit($request);

        if ($this->hasRole('ROLE_ADMIN')) {
            $pagination = $this->getAdminUserPagination($request, $form->getData(), 5);
        } else {
            $pagination = $this->getUserPagination($request, $form->getData(), 5);
        }

        return $this->render(
            'UserBundle:SubUser:index.html.twig',
            array(
                'entities' => $pagination,
                'form' => $form->createView()
            )
        );
    }

    /**
     * New subuser
     *
     * @param  Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        // Has user granted role?
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_SUBUSER_MANAGER'));

        $user = $this->getUserManager()->createUser();
        $user->setEnabled(true);

        $form = $this->getFormFactory(self::FORM_FACTORY_REGISTRATION)->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isValid()) {

            // Add the parentId if not ROLE_ADMIN
            if ($this->hasRole('ROLE_CUSTOMER_SUBUSER_MANAGER')) {
                $user->setParentId(
                    $this->getParentId()
                );
            }

            // Setup the roles
            $user->setUsedRoles();

            // Insert the user
            $this->getUserManager()->updateUser($user);

            $this->get('session')->getFlashBag()->add('notice', 'subuser.flash.create.success');

            return $this->redirect(
                $this->generateUrl(
                    'avl_subuser_edit',
                    array(
                        'id' => $user->getId()
                    )
                )
            );
        }

        return $this->render(
            'FOSUserBundle:Registration:register.html.twig',
            array(
                'form' => $form->createView(),
                'path' => 'avl_subuser_new'
            )
        );
    }

    /**
     * Edit subuser
     *
     * @param  Request $request
     * @param  $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $userId)
    {
        // Has user granted role?
        $this->hasGranted(
            array(
                'ROLE_ADMIN',
                'ROLE_CUSTOMER_SUBUSER_MANAGER'
            )
        );

        // Find the user to edit
        $user = $this->findUser($userId);

        // Add Data and the request to the form
        $formFactory = $this->getFormFactory(self::FORM_FACTORY_PROFILE);

        // View the roles to select which
        // role user can use
        $formFactory->setRoleView(true);

        // View the enabled checkbox to
        // select if user is enabled
        $formFactory->setEnabledView(true);

        // View the choice to choose
        // if user is admin or customer
        // Only in admin-view
        if ($this->hasRole('ROLE_ADMIN')) {
            $formFactory->setUser($user);
            $formFactory->setAdminView(true);
        }

        // Create the form
        $form = $formFactory->createForm();

        // Add Data and the request to the form
        $form->setData($user);
        $form->handleRequest($request);

        // If method was POST and is valid,
        // then we save and redirect the
        // output.
        if ($request->getMethod() == 'POST' && $form->isValid()) {

            // Which type of user was set?
            if ($this->hasRole('ROLE_ADMIN')) {
                // Setup Adminrole
                if (count($user->getRoles()) > 2 && in_array('ROLE_ADMIN', $user->getRoles())) {
                    $user->setAdminRoles();
                }
            }

            // Update the user
            $this->getUserManager()->updateUser($user);

            // Crop image
            if ($this->cropImage($user)) {
                return $this->redirectSubUser('subuser.flash.edit.success');
            }
        }
        // Get and create the FOSUserbundleForm
        // Render my view with additional data
        return $this->render(
            'UserBundle:Profile:edit.html.twig',
            array(
                'userId' => $userId,
                'form' => $form->createView(),
                'path' => 'avl_subuser_edit',
                'profilePicturePath' => $user->getProfilePicturePath()
            )
        );
    }

    /**
     * Remove subuser
     *
     * @param  Request $request
     * @param  $id
     * @return RedirectResponse
     */
    public function removeAction(Request $request, $userId)
    {
        // Has user granted role?
        $this->hasGranted(
            array(
                'ROLE_ADMIN',
                'ROLE_CUSTOMER_SUBUSER_MANAGER'
            )
        );

        if ($request->getMethod() == 'DELETE') {
            try {
                $em = $this->getDoctrine()->getManager();
                // Find the user to delete
                $user = $this->findUser($userId);

                if (null !== $user && is_object($user)) {
                    $em->remove($user);
                    $em->flush();
                    return $this->redirectSubUser('subuser.flash.remove.success');
                } else {
                    throw new AccessDeniedException('This user does not have access to this section.');
                }
            } catch (AccessDeniedException $e) {
                $this->get('session')->getFlashBag()->add('error', 'subuser.flash.remove.error');
            }
        }

        return $this->redirectSubUser();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function switchOnAction()
    {
        return $this->switchAction('fos_user_profile_show');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function switchOffAction()
    {
        return $this->switchAction('avl_subuser');
    }

    /**
     * @param $route
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function switchAction($route)
    {
        $this->container
            ->get('set_session_service')
            ->setUser(
                $this->getUser()
            );

        try {
            return $this->redirect(
                $this->generateUrl($route)
            );
        } catch (\Exception $e) {
            return $this->render(
                'UserBundle:Content:404.html.twig',
                array()
            );
        }
    }

    /**
     * Find the correct user by
     * id and parentId
     *
     * @param  $id
     * @return \FOS\UserBundle\Model\UserInterface
     */
    private function findUser($userId)
    {
        // Get the userManager
        $userManager = $this->getUserManager();

        // If user is admin
        if ($this->hasRole('ROLE_ADMIN')) {
            return $userManager->findUserBy(
                array('id' => (integer)$userId)
            );
        } else {
            // If user is customer
            return $userManager->findUserBy(
                array(
                    'id' => (integer)$userId,
                    'parentId' => (integer)$this->getParentId()
                )
            );
        }
    }

    /**
     * Get the userManager from FOSUserBundle
     *
     * @return object
     */
    private function getUserManager()
    {
        return $this->get(self::USER_MANAGER);
    }

    /**
     * Get the formFactory from FOSUserBundle
     *
     * @param  null $formFactory
     * @return null|object
     */
    private function getFormFactory($formFactory = '')
    {
        return (null !== $formFactory) ? $this->get($formFactory) : null;
    }

    /**
     * Redirect to the subuseroverview
     *
     * @param  null $message
     * @return RedirectResponse
     */
    private function redirectSubUser($message = '')
    {
        if (null !== $message && !$this->get('session')->getFlashBag()->has('error')) {
            $this->get('session')->getFlashBag()->add('notice', (string)$message);
        }
        return $this->redirect(
            $this->generateUrl('avl_subuser')
        );
    }

    /**
     * Get the parentId or if null then
     * the userId.
     *
     * @return mixed
     */
    private function getParentId()
    {
        return
            (null !== $this->getUser()->getParentId()) ?
                $this->getUser()->getParentId() : $this->getUser()->getId();
    }

    /**
     * Crop the current user avatar
     *
     * @param  $user
     * @return bool
     */
    private function cropImage($user)
    {
        try {
            if ($user->getImageCropY() != '') {
                // Get the cropimage-service
                $imageService = $this->container->get('image_service');

                // Setup the cropImage-Service
                $imageService->setImageCropY($user->getImageCropY());
                $imageService->setImageCropX($user->getImageCropX());
                $imageService->setImageCropHeight($user->getImageCropHeight());
                $imageService->setImageCropWidth($user->getImageCropWidth());
                $imageService->setImagePath($user->getProfilePictureFile()->getPathname());

                // crop the image
                $imageService->cropImage();
            }
            return true;
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }
        return false;
    }
}
