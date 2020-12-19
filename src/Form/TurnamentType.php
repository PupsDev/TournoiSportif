<?php

namespace App\Form;

use App\Entity\Turnament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\RoundType;
class TurnamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
         
             ->add('name', TextType::class,[
                        'label' => 'Nom du tournoi :',

                        'attr'=>[
                            'placeholder' => "Nom du tournoi",

                        ]
                    ])
             ->add('rounds', CollectionType::class, [
                   'entry_type'    => RoundType::class,
                   'entry_options' => [
                       'label' => 'Tour :',
                   ],
                   'label'        => 'Tours :',
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
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Turnament::class,
        ]);
    }
}
