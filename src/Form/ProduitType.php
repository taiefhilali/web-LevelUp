<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        ChangelabelName
        $builder
            ->add('nom')
            ->add('reference')
            ->add('prix')
            ->add('description')
            ->add('promotion')
            ->add('imageFile',VichImageType::class,array('data_class' => null,'label'=>'Image produit'),['label'=>'insert image'])
//array('empty_data' => ''),
            ->add('idUser',EntityType::class, [
                    'label'=>'E-mail du Fournisseur',
                    'class'=>User::class,
                    'choice_label'=>'email']
            )
            ->add('idCategorie', EntityType::class, [
                'label'=>'Catégorie',
                'class'=>Categorie::class,
                'choice_label'=>'nomCategorie',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
