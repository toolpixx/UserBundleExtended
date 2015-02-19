<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Form\Type;

use Avl\UserBundle\Entity\User as User;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

use Symfony\Component\Locale as Locale;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ProfileFormType
 * @package Avl\UserBundle\Form\Type
 */
class ProfileFormType extends BaseType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $this->buildUserForm($builder, $options);

        // Remove ask for current_password
        // Add profilePicture for upload...
        $builder
            //->remove('username')
            ->remove('current_password')
            ->remove('email')
            ->remove('username')
            ->add('username', 'text', array(
                'required' => true,
                'label' => 'label.username',
                'attr' => array(
                    'size' => 20,
                    'style' => 'width:350px'
                )
            ))
            ->add('email', 'email', array(
                'required' => true,
                'label' => 'label.email',
                'attr' => array(
                    'size' => 20,
                    'style' => 'width:350px'
                )
            ));

            // If roleView is true
            if (isset($options['roleView']) && $options['roleView']) {
                $builder->add('usedRoles', 'choice', array(
                    'property_path' => 'roles',
                    'choices' => User::getUsedRoles(),
                    'mapped' => true,
                    'expanded' => true,
                    'multiple' => true,
                    'label' => 'Rollen',
                    'attr' => array(
                        'style' => 'width:200px'
                    )
                ));
            }

            // If enabledView is true
            if (isset($options['enabledView']) && $options['enabledView']) {
                $builder->add('enabled', 'checkbox', array(
                    'label' => 'label.enabled',
                    'required' => false
                ));
            }

            $builder->add('locale', 'choice', array(
                    'choices' => User::getLocaleNames(),
                    'label' => 'label.locale',
                    'attr' => array(
                        'style' => 'width:200px'
                    )
                )
            )
            ->add('profilePictureFile', 'file',
                array(
                    'label' => 'label.avatar',
                    'required' => false
                )
            )
            ->add('imageCropY', 'hidden')
            ->add('imageCropX', 'hidden')
            ->add('imageCropHeight', 'hidden')
            ->add('imageCropWidth', 'hidden')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        // Not use yet...
        //$resolver->setRequired(array(
        //    'user'
        //));

        // Set roleView
        $resolver->setDefaults(array(
            'roleView'  => null,
        ));

        // Set enabledView
        $resolver->setDefaults(array(
            'enabledView'  => null,
        ));

        // Set user
        $resolver->setDefaults(array(
            'user'  => null,
        ));
    }

    /**
     * @return string
     */
    public function getParent() {
        return 'fos_user_profile';
    }

    /**
     * @return string
     */
    public function getName() {
        return 'avl_user_profile';
    }
}
