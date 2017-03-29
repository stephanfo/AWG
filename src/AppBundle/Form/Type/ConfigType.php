<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ConfigType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('applicationTheme', ChoiceType::class, array(
                'choices'  => array(
                    'Cerulean' => 'cerulean',
                    'Cosmo' => 'cosmo',
                    'Cyborg' => 'cyborg',
                    'Darkly' => 'darkly',
                    'Flatly' => 'flatly',
                    'Journal' => 'journal',
                    'Lumen' => 'lumen',
                    'Paper' => 'paper',
                    'Readable' => 'readable',
                    'Sandstone' => 'sandstone',
                    'Simplex' => 'simplex',
                    'Slate' => 'slate',
                    'Solar' => 'solar',
                    'Spacelab' => 'spacelab',
                    'Superhero' => 'superhero',
                    'United' => 'united',
                    'Yeti' => 'yeti',
                ),
                'required' => false,
                'empty_data' => null,
                'placeholder' => 'Sélectionez un theme'
            ))
            ->add('applicationIntroMessage')
            ->add('applicationSellFiles', ChoiceType::class, array(
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false,
                ),
            ))
            ->add('applicationSellFilesAllowDownload', ChoiceType::class, array(
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false,
                ),
            ))
            ->add('applicationSellFilesForceDownload', ChoiceType::class, array(
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false,
                ),
            ))
            ->add('applicationSellFilesEmailSender')
            ->add('applicationSellFilesEmailSubject')
            ->add('applicationSellFilesEmailBody')
            ->add('galleryQuickLink', ChoiceType::class, array(
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false,
                ),
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
            'data_class' => 'AppBundle\Entity\Config'
        ));
    }

}
