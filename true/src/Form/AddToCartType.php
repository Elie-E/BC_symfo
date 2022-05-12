<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('quantity', IntegerType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'type' => 'number',
                'value' => 1,
                'min' => 1,
                'aria-label' => 'search',
                'style' =>'width: 100px'
                ]
            ])
        ->add('addToCart', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary', 'icon_after' => 'fas fa-shopping-cart ml-1', 'style' => 'display: inline-block']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
