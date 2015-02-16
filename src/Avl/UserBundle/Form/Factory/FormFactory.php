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

class FormFactory implements FactoryInterface
{
    private $formFactory;
    private $name;
    private $type;
    private $validationGroups;
    private $user;

    public function __construct(FormFactoryInterface $formFactory, $name, $type, array $validationGroups = null)
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
    }

    public function createForm()
    {
        return $this->formFactory->createNamed(
            $this->name,
            $this->type,
            null,
            array(
                'validation_groups' => $this->validationGroups,
                'user' => $this->user
            )
        );
    }

    /**
     * Set the userdata to the form
     *
     * @param $user
     */
    public function setUser($user) {
        $this->user = $user;
    }
}