<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchBar', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'nav_search-input', 'name' => 'txt', 'onmouseout' => "document.search.txt.value = ''"]
            ])
            // ->add('rechercher', SubmitType::class, [
            //     'attr' => ['class' => 'nav-search-button btn btn-sm btn-dark']
            // ])
            ;
    }

}
