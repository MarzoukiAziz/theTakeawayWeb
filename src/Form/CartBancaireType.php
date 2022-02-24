<?php

namespace App\Form;

use App\Entity\CartBancaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartBancaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero',TextType::class)
            ->add('nomcomplet',TextType::class)
            ->add('datexp',TextType::class)
            ->add('cvv',TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CartBancaire::class,
        ]);
    }
}
