<?php

namespace App\Form;

use App\Entity\ProductImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image_product', FileType::class, [
                        'label'=> 'image',
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new File([
                                'maxSize' => '10024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg',
                                ],
                                'mimeTypesMessage' => 'Fichier invalide',
                                'maxSizeMessage' => 'Fichier trop lourd'
                            ])
                        ]
                    ])
            ->add('altImage', TextType::class, [
            'required' => false,
            ])
            // ->add('category', CategoryType::class)
            // ->add('product', ProductType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductImage::class,
        ]);
    }
}
