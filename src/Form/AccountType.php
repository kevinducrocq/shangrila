<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre prénom',
                ], 'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre nom',
                ], 'label' => 'Nom'
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Votre email',
                ], 'label' => 'Email'
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre adresse',
                ], 
                'label' => 'Adresse',
                'required' => false,
            ])
            ->add('zipcode', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Votre code postal',
                ], 'label' => 'Code postal',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre ville',
                ], 'label' => 'Ville',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
