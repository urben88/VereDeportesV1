<?php

namespace App\Form;

//TODO Entidades
use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('email',EmailType::class)
            ->add('password',PasswordType::class)
            ->add('Repeat_password',PasswordType::class, ['mapped'=>false]) //! Para evitar que lo meta en el objeto
            // ->add('admin',CheckboxType::class,['mapped'=>false])
            ->add('save',SubmitType::class, ['label'=>'Registrar'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
