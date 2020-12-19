<?php

namespace App\Form;

use App\Entity\GroupStage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType ;
class GroupStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('setPerGroupStage', NumberType::class,[
                        'label' => 'Nombre de sets :',

                        'attr'=>[
                            'placeholder' => "Nombre de sets",

                        ]
                    ])
              ->add('setPoints', NumberType::class,[
                        'label' => 'Nombre de points par set :',

                        'attr'=>[
                            'placeholder' => "Nombre de points par set",

                        ]
                    ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GroupStage::class,
        ]);
    }
}
