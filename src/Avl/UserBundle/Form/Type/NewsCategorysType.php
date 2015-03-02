<?php

namespace Avl\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use /** @noinspection PhpDeprecationInspection */
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsCategorysType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'label.news.categorys.name',
                'required' => true
            ))
            ->add('path', 'text', array(
                'label' => 'label.path',
                'required' => false
            ))
            ->add('enabled', 'checkbox', array(
                'label' => 'label.news.categorys.enabled',
                'required' => false
            ))
            ->add('internal', 'checkbox', array(
                'label' => 'label.news.categorys.internal',
                'required' => false
            ))
        ;

        /**
         * Setup the path from title if path is emtpy
         */
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $newsCategory = $event->getData();
            $newsCategory['path'] = $event->getForm()->getData()->setPathReplace($newsCategory);
            $event->setData($newsCategory);
        });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Avl\UserBundle\Entity\NewsCategorys'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avl_news_categorys';
    }
}
