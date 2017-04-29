<?php

namespace CartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use CartBundle\Entity\Price;

class PriceType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('quantity')
                ->add('price')
                ->add('discount', EntityType::class, array(
                    'class' => 'CartBundle:Discount',
                    'choice_label' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'placeholder' => 'Permanent',
                    'required' => false,
                ))
                ->add('save', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Price::class
        ));
    }

}
