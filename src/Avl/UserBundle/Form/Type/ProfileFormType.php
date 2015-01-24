<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Form\Type;

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
        /**
         * Get the parent from FOSUserBundle
         */
        parent::buildForm($builder, $options);

        /**
         * Remove ask for current_password
         * Add profilePicture for upload...
         */
        $builder
            //->remove('username')
            ->remove('current_password')
            ->add('profilePictureFile', 'file')
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
