<?php

namespace App\Form;

use App\Entity\Adresse;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
// use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', NumberType::class, [
                'required' => false,
            ])
            ->add('line1', TextType::class, [
                'label' => 'ligne 1'
            ])
            ->add('line2', TextType::class, [
                'label' => 'ligne 2',
                'required' => false
            ])
            ->add('line3', TextType::class, [
                'label' => 'ligne 3',
                'required' => false
            ])
            ->add('code', TextType::class, [
                'required' => false,
                'label' => 'code postal'
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'label' => 'Localité'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
