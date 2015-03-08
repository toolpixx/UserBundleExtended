<?php

namespace Avl\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use /** @noinspection PhpDeprecationInspection */
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OAuthType extends AbstractType
{
    /**
     * @var
     */
    private $builder;

    /**
     * @var
     */
    private $choices;

    /**
     * @var
     */
    private $account;

    /**
     * @param array $choices
     * @param array $account
     */
    public function __construct(array $choices = [], array $account = null)
    {
        $this->choices = $choices;
        $this->account = $account;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->builder = $builder;
        $this->builder
            ->add('info', 'hidden')
            ->add('accounts', 'choice', array(
                'property_path' => 'accounts',
                'choices' => $this->choices,
                'mapped' => true    ,
                'expanded' => true,
                'multiple' => true,
                'label' => 'label.facebook.accounts',
                'attr' => array(
                    'style' => 'width:400px',
                    'is_selected' => 123460
                )
            ))
        ;

        if (sizeOf($this->account) > 0) {
            $this->builder->setEmptyData($this->account);
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => 'Avl\UserBundle\Entity\OAuth'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avl_oauth';
    }
}
