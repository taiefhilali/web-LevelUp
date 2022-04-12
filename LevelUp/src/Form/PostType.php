<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Asserts;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content',CKEditorType::class)
            ->add('datep')
            ->add('idUser',EntityType::class,['class'=>User::class,'choice_label'=>'email'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
