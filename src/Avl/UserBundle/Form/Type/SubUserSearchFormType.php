<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 20.02.15
 * Time: 21:56
 */
namespace Avl\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SubUserSearchFormType
 * @package Avl\UserBundle\Form\Type
 */
class SubUserSearchFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder
            ->add('query', 'text');
    }

    /**
     * @return string
     */
    public function getName() 
    {
        return 'page_filter';
    }
}
