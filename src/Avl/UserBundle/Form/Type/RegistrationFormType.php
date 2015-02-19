<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avl\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType {
    private $class;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->remove('username')
            ->remove('email')
            ->remove('plainPassword')
            ->add('username', null, array(
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'style' => 'width:350px'
                )
            ))
            ->add('email', 'email', array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'style' => 'width:350px'
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
                    'label' => 'form.password'
                ),
                'second_options' => array(
                    'label' => 'form.password_confirmation'
                ),
                'invalid_message' => 'fos_user.password.mismatch'
            ))
        ;
    }

    /**
     * @return string
     */
    public function getParent() {
        return 'fos_user_registration';
    }

    /**
     * @return string
     */
    public function getName() {
        return 'avl_user_registration';
    }
}
