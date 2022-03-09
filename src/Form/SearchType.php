<?php

namespace App\Form;

use App\Entity\SearchData;
use App\Entity\BlogClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q',TextType::class, [
                'label' => false,
                'required'=>false,
                'attr' => [
                    'placeholder' => ' rechercher '
                ],
            ]) ->add('s',TextType::class, [
                'label' => false,
                'required'=>false,
                'attr' => [
                    'placeholder' => ' rechercher '
                ],
            ])
            ->add('c',TextType::class, [
                'label' => false,
                'required'=>false,
                'attr' => [
                    'placeholder' => ' rechercher '
                ],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'methode'=>'GET',
            'csrf_protection'=>false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}