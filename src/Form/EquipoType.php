<?php

namespace App\Form;

use App\Entity\Equipo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\UserRepository;


class EquipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deporte', ChoiceType::class,[
            'mapped' => false,
            'choices'  => [
                'Futbol' => 'futbol',
                'Baloncesto' => 'baloncesto',
            ]])
            ->add('nombre')
            ->add('capitan', EntityType::class,[
                'class' => User::class,
                'query_builder' => function (UserRepository $em){
                    return $em->createQueryBuilder('u')
                    ->where('u.capitan = :capitan')
                    ->setParameter(':capitan', false);
                },
                'choice_label' => 'email',
                'mapped'=>false

            ])
            ->add('save',SubmitType::class,['label'=>'Crear']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipo::class,
        ]);
    }
}
