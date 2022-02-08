<?php

namespace App\Form;

use App\Entity\Food;
use App\Entity\Category;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FoodFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name'
            ])

            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('Description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['rows' => 10]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix'
            ])
            ->add('img', FileType::class, ['data_class' => null, 'label' => 'Image', 'required' => false, 'mapped' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Food::class,
        ]);
    }
}
