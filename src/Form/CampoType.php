<?php

namespace App\Form;

use App\Entity\Campo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CampoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('Deporte', ChoiceType::class,[
                'mapped' => false,
                'choices'  => [
                    'Futbol' => 'futbol',
                    'Baloncesto' => 'baloncesto',
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Campo::class,
        ]);
    }
}
