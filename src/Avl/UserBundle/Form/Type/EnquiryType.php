<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 12.01.15
 * Time: 22:35
 */
namespace Avl\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EnquiryType
 * @package Avl\UserBundle\Form\Type
 */
class EnquiryType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('name', 'text', array(
            'label' => 'label.username',
            'required' => true
        ));
        $builder->add('email', 'email', array(
            'label' => 'label.email',
            'required' => true
        ));
        $builder->add('subject', 'text', array(
            'label' => 'label.subject',
            'required' => true
        ));
        $builder->add('body', 'textarea', array(
            'label' => 'label.body',
            'required' => true
        ));
        $builder->add('attachment', 'file', array(
            'label' => 'label.file',
            'required' => false
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'enquiry';
    }
}