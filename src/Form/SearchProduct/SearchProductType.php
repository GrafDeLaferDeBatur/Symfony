<?php

namespace App\Form\SearchProduct;

use App\Entity\Category;
use App\Entity\Color;
use App\Form\Object\SearchProduct;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->setMethod('GET')
            ->add('min_price', IntegerType::class,[
                'label' => 'product_search.min_price',
                'empty_data' => null,
                'required' => false,
                'attr' => ['data-info' => ''],
            ])
            ->add('max_price', IntegerType::class,[
                'label' => 'product_search.max_price',
                'empty_data' => null,
                'required' => false,
                'attr' => ['data-info' => ''],
            ])
            ->add('category', EntityType::class,[
                'label' => 'product_search.category',
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => ['data-info' => ''],
            ])
            ->add('color', EntityType::class, [
                'label' => 'product_search.color',
                'class' => Color::class,
                'choice_label' => 'color',
                'required' => false,
                'attr' => ['data-info' => ''],
            ])
            ->add('min_weight', IntegerType::class,[
                'label' => 'product_search.min_weight',
                'empty_data' => null,
                'required' => false,
                'attr' => ['data-info' => ''],
            ])
            ->add('max_weight', IntegerType::class,[
                'label' => 'product_search.max_weight',
                'empty_data' => null,
                'required' => false,
                'attr' => ['data-info' => ''],
            ])
            ->add('substring', TextType::class,[
                'label' => 'product_search.search_by',
                'empty_data' => null,
                'required' => false,
                'attr' => [
                    'data-info' => '',
                    'class' => 'typeahead',
                ],
            ])
            ->add('sort', ChoiceType::class,[
                'label' => 'product_search.sort_by',
                'choices'=>[
                    '' => null,
                    'price' => 'p.price',
                    'weight' => 'u.weight'
                ],
                'attr' => ['data-info' => ''],
            ])
            ->add('page', HiddenType::class,[
                'mapped' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'product_search.search'
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchProduct::class,
        ]);
    }

}