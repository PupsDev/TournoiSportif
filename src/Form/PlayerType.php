<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('surname')
            ->add('email')
            ->add('roles', ChoiceType::class, [
                
                'choices' => 
               [
                'Capitaine' => 'ROLE_CAPTAIN',
                'Joueur' => 'ROLE_PLAYER',

               ]
                ,
                'expanded' => true,
               
            ])
            ->add('password')
            ->add('team')
            
            ->add('level', ChoiceType::class, [
                
                'choices' => 
               [
                'Pro' => 'Pro',
                'Elite' => 'Elite',
                'N2' => 'N2',
                'N3' => 'N3',
                'Régional' => 'Régional',
                'Départemental' => 'Départemental',
                'Loisir' => 'Loisir',

               ]
               
            ])
        ;
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
        function ($rolesArray) {
             // transform the array to a string
             return count($rolesArray)? $rolesArray[0]: null;
        },
        function ($rolesString) {
             // transform the string back to an array
             return [$rolesString];
        }
    ));
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
