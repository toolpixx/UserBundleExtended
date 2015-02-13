<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Form\Type;

use Avl\UserBundle\Entity\User as User;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

/**
 * Class ProfileFormType
 * @package Avl\UserBundle\Form\Type
 */
class ProfileFormType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Get the parent from FOSUserBundle
        parent::buildForm($builder, $options);

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
                    'style' => 'width:200px'
                )
            ))
            ->add('email', 'email', array(
                'required' => true,
                'label' => 'label.email',
                'attr' => array(
                    'size' => 20,
                    'style' => 'width:200px'
                )
            ))
            ->add('locale', 'choice', array(
                'choices' => User::getLocaleNames(),
                'label' => 'label.locale',
                'attr' => array(
                    'style' => 'width:200px'
                )
            ))
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
}
