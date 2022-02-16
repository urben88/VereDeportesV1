<?php

namespace App\Form;

use App\Entity\Reserva;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha',DateTimeType::class,[
                'attr' => ['class' => ''],
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Dia',
                    'hour' => 'Hora', 'minute' => 'Minuto'
                ],
            ])
            ->add('save', SubmitType::class,[
                'label'=>'Solicitar'
            ])
           
            // ->add('id_usuario')
            // ->add('id_campo')
            // ->add('id_profesor')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
        ]);
    }
}
