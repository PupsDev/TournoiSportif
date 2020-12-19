<?php

namespace App\Form;

use App\Entity\Round;
use App\Entity\GroupStage;
use App\Form\GroupStageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RoundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder  ->add('playerPerRoundStage', NumberType::class,[
                        'label' => 'Meilleurs joueurs en sortie :',

                        'attr'=>[
                            'placeholder' => "Meilleurs joueurs en sortie",

                        ]
                    ])
                  ->add('loserBracket',CheckboxType::class, [
                        'label'    => 'Tournoi consolante',
                        'required' => false,
                    ])
                  ->add('doubleElimination',CheckboxType::class, [
                        'label'    => 'Double Elimination',
                        'required' => false,
                    ])
                  ->add('lastRound',CheckboxType::class, [
                        'label'    => 'Dernier Round',
                        'required' => false,
                    ])
                  ->add('groupStages', CollectionType::class, [
                         'entry_type'    => GroupStageType::class,
                         'entry_options' => [
                             'label' => 'Poule :',
                         ],
                         'label'        => 'Poules',
                         'allow_add'    => true,
                         'allow_delete' => true,
                         'prototype'    => true,
                         'required'     => false,
                         'by_reference' => false,
                     ]);
              ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Round::class,
        ]);
    }
}
