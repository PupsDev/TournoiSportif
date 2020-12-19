<?php

namespace App\Form;

use App\Entity\Organizer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class OrganizerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('surname')
            ->add('email')
            ->add('password')
            ->add('structure')
             ->add('roles', ChoiceType::class, [
                
                'choices' => 
               [
                'Organizer' => 'ROLE_ORGANIZER',

               ],   
                           
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
            'data_class' => Organizer::class,
        ]);
    }
}
