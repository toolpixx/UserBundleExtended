<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 12.01.15
 * Time: 16:40
 */
namespace Avl\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class ChangePasswordFormType
 * @package Avl\UserBundle\Form\Type
 */
class ChangePasswordFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => new UserPassword(),
            'attr' => array(
                'style' => 'width:180px'
            )
        ))
        ->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array(
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'style' => 'width:180px'
                )
            ),
            'first_options' => array(
                'label' => 'form.new_password'
            ),
            'second_options' => array(
                'label' => 'form.new_password_confirmation'
            ),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'fos_user_change_password';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avl_user_change_password';
    }
}
