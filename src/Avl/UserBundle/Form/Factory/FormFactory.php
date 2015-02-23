<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 16.02.15
 * Time: 14:34
 */
namespace Avl\UserBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use FOS\UserBundle\Form\Factory\FactoryInterface;

/**
 * Class FormFactory
 * @package Avl\UserBundle\Form\Factory
 */
class FormFactory implements FactoryInterface
{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $type;

    /**
     * @var array
     */
    private $validationGroups;

    /**
     * @var
     */
    private $user;

    /**
     * @var
     */
    private $roleView;

    /**
     * @var
     */
    private $enabledView;

    /**
     * @var
     */
    private $adminView;

    /**
     * @param FormFactoryInterface $formFactory
     * @param $name
     * @param $type
     * @param array $validationGroups
     */
    public function __construct(FormFactoryInterface $formFactory, $name, $type, array $validationGroups = null)
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm()
    {

        return $this->formFactory->createNamed(
            $this->name,
            $this->type,
            null,
            array(
                'validation_groups' => $this->validationGroups,
                'user' => $this->user,
                'roleView' => $this->roleView,
                'enabledView' => $this->enabledView,
                'adminView' => $this->adminView
            )
        );
    }

    /**
     * Set the userdata to the form
     *
     * @param $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Set the roleView parameter to
     * view checkboxes or not
     *
     * @param $roleView
     */
    public function setRoleView($roleView)
    {
        $this->roleView = $roleView;
    }

    /**
     * Set the enabledView parameter to
     * view checkbox or not
     *
     * @param $enabledView
     */
    public function setEnabledView($enabledView)
    {
        $this->enabledView = $enabledView;
    }

    /**
     * Set the adminView parameter to
     * view choice or not
     *
     * @param $adminView
     */
    public function setadminView($adminView)
    {
        $this->adminView = $adminView;
    }
}