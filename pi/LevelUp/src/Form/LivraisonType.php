<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idCommande',EntityType::class,['class'=> Commande::class, 'choice_label'=>'idCommande','label'=>'Commande'
            ])
            ->add('etatLivraison', ChoiceType::class,array('choices'=>['En cours'=> 'en cours', 'Confirmée'=>'confirmée', 'livrée'=>'livrée'],
                'expanded' => true))
            ->add('idUser',EntityType::class,['class'=> User::class, 'choice_label'=>'email','label'=>'Utilisateur'
            ])
            ->add('date')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'input'=>'string'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
