<?php

namespace App\Form;

use App\Entity\Campo;
use App\Entity\Reserva;
use App\Repository\CampoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security as CoreSecutity;
class ReservaType extends AbstractType
{
    public $security;

    public function __construct(CoreSecutity $security){
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha',DateTimeType::class,[
                'attr' => ['class' => ''],
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Dia',
                    'hour' => 'Hora', 'minute' => 'Minuto'
                ],
                'hours'=>[16,17,18,19,20,21,22],
                'years'=>[2022,2023,2024,2025],
                'minutes'=>[0,30]
                
            ])
            ->add('campos',EntityType::class,[
                'class'=>Campo::class,
                'choice_label'=>"nombre",
                "mapped"=>false,
                "label"=>"Campos disponibles:",
                "query_builder" => function(CampoRepository $campo){
                    return $campo->createQueryBuilder('c')
                    ->where('c.deporte = :tipo')
                    ->setParameter('tipo',$this->security->getUser()->getEquipo()->getDeporte());
                }

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
