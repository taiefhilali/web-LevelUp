<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Vich\UploaderBundle\Form\Type\VichImageType;
class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('repeatPassword',PasswordType::class)
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('tel')
            ->add('dns', DateType::class, [
            
                'input' => 'datetime',
                'widget' => 'single_text',
                
                'years' => range(date('Y'), date('Y')+100),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ])
            ->add('sexe', ChoiceType::class,array('choices'=>['Femme'=> 'femme',
            'Homme'=>'homme']))

            ->add('imageFile',VichImageType::class,array('data_class' => null),['label'=>'insert_image'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
