<?php

namespace App\Form;

use App\Entity\Livraison;
use App\Entity\Reclamation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('idLivraison',EntityType::class,['class'=> Livraison::class, 'choice_label'=>'idLivraison'
            ]);

        /*
->add('idLivraison', EntityType::class, [
    'class' => Livraison::class,
    'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->Join('u.idCommande', 'C')
            ->andwhere('c.idUser =:idUser')
            ->setParameter('idUser', 200);
    },
    'choice_label' => 'idLivraison',
]);
        ;*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
