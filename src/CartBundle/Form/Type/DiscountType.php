<?php

namespace CartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DiscountType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title')
                ->add('comment')
                ->add('startTime', DateTimeType::class, array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy HH:mm:ss',
                    'placeholder' => 'Permanent'
                ))
                ->add('stopTime', DateTimeType::class, array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy HH:mm:ss',
                    'placeholder' => 'Permanent'
                ))
                ->add('discount')
                ->add('discountStart')
                ->add('discountStop')
                ->add('active')
                ->add('save', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CartBundle\Entity\Discount'
        ));
    }

}
