<?php

namespace Avl\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use /** @noinspection PhpDeprecationInspection */
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FaqType extends AbstractType
{
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

        $builder
            ->add('question', 'text', array(
                'label' => 'label.faq.question',
                'required' => true
            ))
            ->add('answer', 'textarea', array(
                'label' => 'label.faq.answer',
                'attr' => array(
                    'rows' => '20'
                ),
                'required' => false
            ))
            ->add('path', 'text', array(
                'label' => 'label.faq.path',
                'required' => false
            ))
            ->add('category', 'entity', array(
                'class' => 'UserBundle:FaqCategorys',
                'label' => 'label.faq.category',
                'empty_value' => 'form.select.categorys.please.select',
                'property' => 'name',
                'required' => false
            ))
            ->add('related', 'entity', array(
                'class' => 'UserBundle:Faq',
                'label' => 'label.faq.related',
                'empty_value' => 'form.select.categorys.please.select',
                'property' => 'question',
                'multiple' => true,
                'required' => false
            ))
            ->add('enabled', 'checkbox', array(
                'label' => 'label.faq.enabled',
                'required' => false
            ))
            ->add('internal', 'checkbox', array(
                'label' => 'label.faq.internal',
                'required' => false
            ))
            ->add('enabledDate', 'datetime', array(
                'label' => 'label.faq.enabledDate'
            ))
            ->add('enabledExpiredDate', 'checkbox', array(
                'label' => 'label.faq.enabledExpiredDate',
                'required' => false
            ))
            ->add('expiredDate', 'datetime', array(
                'label' => 'label.faq.expiredDate'
            ))
        ;

        $this->setPreSubmitFormEvent();
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Avl\UserBundle\Entity\Faq'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avl_faq';
    }

    /**
     * Setup PRE_SUBMIT
     * Setup the path from title if path is emtpy
     */
    private function setPreSubmitFormEvent()
    {
        $this->builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $news = $event->getData();
            $news['path'] = $event->getForm()->getData()->setPathReplace($news);
            $event->setData($news);
        });
    }
}
