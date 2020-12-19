<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Turnament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\TurnamentType;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                    ->add('name', TextType::class,[
                        'attr'=>[
                            'placeholder' => "Nom de l'événement"
                        ]
                    ])
                    ->add('date', DateTimeType::class,[
                        'attr'=>[
                            'placeholder' => "Date de l'événement"
                        ]
                    ])
                    ->add('location', TextType::class,[
                        'attr'=>[
                            'placeholder' => "Lieu de l'événement"
                        ]
                    ])
                    ->add('sport', ChoiceType::class, [
                
                          'choices' => 
                         [
                          'Football' => 'sports_soccer',
                          'Basketball' => 'sports_basketball',
                          'Tennis' => 'sports_tennis',
                          'Volley' => 'sports_volleyball',
                          'Pétanque' => 'sports_baseball',
                          'Baseball' => 'sports_cricket',
                          'Esport' => 'sports_esports',

                         ]
                         
                      ])
                    ->add('playerPerTeam', RangeType::class, [
                          'attr' => [
                              'min' => 1,
                              'max' => 11,
                              'value' => 5
                          ]
                    ])
                    ->add('turnaments', CollectionType::class, [
                   'entry_type'    => TurnamentType::class,
                   'entry_options' => [
                       'label' => 'Tournois :',
                   ],
                   'label'        => '',
                   'allow_add'    => true,
                   'allow_delete' => true,
                   'prototype'    => true,
                   'required'     => false,
                   'attr'         => [
                       'class' => 'my-selector',
                       'placeholder' => "Nombre de joueurs par équipe"
                   ],
                   'by_reference' => false,
               ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
