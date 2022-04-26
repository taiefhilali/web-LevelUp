<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories',EntityType::class,[
                'label'=>false,
                'required'=>false,
                'class' =>Categorie::class,
                'expanded' =>true,
                'multiple' =>true

            ])
            ->add('min',NumberType::class,[
                'label' =>false,
                'required'=>false,
                'attr' => [
                    'placeholder' =>'Prix Minimal'
                ]

            ])
            ->add('max',NumberType::class,[
                'label' =>false,
                'required'=>false,
                'attr' => [
                    'placeholder' =>'Prix Maximal'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' =>SearchData::class,
            'method' => 'GET',
            'csrf_protection' =>false

        ]);
    }
    public function getBlockPrefix(){

        return'';
    }
}