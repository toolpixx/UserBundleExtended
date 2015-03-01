<?php

namespace Avl\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use /** @noinspection PhpDeprecationInspection */
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'label.title',
                'required' => true
            ))
            ->add('preface', 'textarea', array(
                'label' => 'label.preface',
                'attr' => array(
                    'rows' => '5'
                ),
                'required' => true
            ))
            ->add('body', 'textarea', array(
                'label' => 'label.content',
                'attr' => array(
                    'rows' => '20'
                ),
                'required' => false
            ))
        ;

        //if (null !== $options['data']->getId()) {
            $builder
                ->add('path', 'text', array(
                    'label' => 'label.path',
                    'required' => false
                ))
            ;
        //}

        $builder
            ->add('link', 'text', array(
                'label' => 'label.link',
                'required' => false
            ))
            ->add('category', 'entity', array(
                'class' => 'UserBundle:NewsCategorys',
                'label' => 'label.category',
                'empty_value' => 'form.select.categorys.please.select',
                'property' => 'name',
                'required' => false
            ))
            ->add('enabled', 'checkbox', array(
                'label' => 'label.news.enabled',
                'required' => false
            ))
            ->add('internal', 'checkbox', array(
                'label' => 'label.internal',
                'required' => false
            ))
            ->add('enabledDate', 'datetime', array(
                'label' => 'label.enabledDate'
            ))
            ->add('expiredDate', 'datetime', array(
                'label' => 'label.expiredDate'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Avl\UserBundle\Entity\News'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avl_news';
    }
}
