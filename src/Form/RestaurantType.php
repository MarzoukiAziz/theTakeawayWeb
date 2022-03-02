<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'Nom Resto',
                'attr'=>[
                    'placeholder'=>'NOM DU RESTAURANT',
                    'class'=>'name'

                ]
            ])
            ->add('adresse')
            ->add('description')
            ->add('heureOuverture')
            ->add('heureFermeture')
            ->add('architecture')
            ->add('telephone')
            ->add('images',FileType::class, array('data_class'=>null,"multiple"=>true,
                'required'=>false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}