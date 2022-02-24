<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\BlogClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BlogClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('contenu')
            ->add('statut')
            ->add('image', FileType::class, array('data_class'=> null,
                'required'=> false))
        ->add('author',EntityType::class,[
            'class'=>Client::class,
            'choice_label'=>'nom',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogClient::class,
        ]);
    }
}
