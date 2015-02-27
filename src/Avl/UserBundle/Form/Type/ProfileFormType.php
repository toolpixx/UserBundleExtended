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
use /** @noinspection PhpDeprecationInspection */
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ProfileFormType
 * @package Avl\UserBundle\Form\Type
 */
class ProfileFormType extends BaseType
{
    /**
     * @var
     */
    private $options;

    /**
     * @var
     */
    private $builder;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->builder = $builder;
        $this->options = $options;

        $this->buildUserForm($builder, $options);

        // Remove ask for current_password
        // Add profilePicture for upload...
        $this->builder
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

        $this->setRolesField();
        $this->setEnabledField();

        $this->builder->add('locale', 'choice', array(
            'choices' => User::getLocaleNames(),
            'label' => 'label.locale',
            'attr' => array(
                'style' => 'width:200px'
            )
        ))
        ->add('profilePictureFile', 'file', array(
            'label' => 'label.avatar',
            'required' => false
        ))
        ->add('imageCropY', 'hidden')
        ->add('imageCropX', 'hidden')
        ->add('imageCropHeight', 'hidden')
        ->add('imageCropWidth', 'hidden');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // Set roleView
        $resolver->setDefaults(array('roleView' => null));

        // Set enabledView
        $resolver->setDefaults(array('enabledView' => null));

        // Set adminView
        $resolver->setDefaults(array('adminView' => null));

        // Set user
        $resolver->setDefaults(array('user' => null));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'fos_user_profile';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avl_user_profile';
    }

    /**
     * Setter for roleView
     */
    private function setRolesField()
    {
        // If adminView is true
        if (isset($this->options['adminView']) && $this->options['adminView']) {
            $roles = array_merge(
                User::getAdminRoles(),
                User::getUsedRoles()
            );
        } else {
            $roles = User::getUsedRoles();
        }

        // If roleView is true
        if (isset($this->options['roleView']) && $this->options['roleView']) {
            $this->builder->add('usedRoles', 'choice', array(
                'property_path' => 'roles',
                'choices' => $roles,
                'mapped' => true,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rollen',
                'attr' => array(
                    'style' => 'width:200px'
                )
            ));
        }
    }

    /**
     * Setter for enabledView
     */
    private function setEnabledField()
    {
        // If enabledView is true
        if (isset($this->options['enabledView']) && $this->options['enabledView']) {
            $this->builder->add('enabled', 'checkbox', array(
                'label' => 'label.enabled',
                'required' => false
            ));
        }
    }
}
