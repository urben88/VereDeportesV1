<?php

namespace App\Form;

use App\Entity\Liga;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deporte',ChoiceType::class,[
                'choices'=>[
                    'baloncesto'=>'baloncesto',
                    'futbol'=>'futbol'
                ]
            ])
            ->add('nombre_liga',TextType::class,['label'=>"Nombre"])
            ->add('fecha',DateTimeType::class,[
                'label'=>'Selecciona dia:',
                'date_widget' =>'single_text',
                 'with_seconds'=>false,
                 'with_minutes'=>false,
                 'hours'=>[0]
            ])
            ->add('save',SubmitType::class,[
                'label'=>'Enviar',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Liga::class,
        ]);
    }
}
