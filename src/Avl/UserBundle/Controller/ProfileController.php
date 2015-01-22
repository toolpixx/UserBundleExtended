<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\ProfileController as BaseProfileController;

/**
 * Class ProfileController
 * @package Avl\UserBundle\Controller
 */
class ProfileController extends BaseProfileController
{
    /**
     * Constructor
     */
    public function __construct() {
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
}