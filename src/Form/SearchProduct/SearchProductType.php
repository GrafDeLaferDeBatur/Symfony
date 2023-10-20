<?php

namespace App\Form\SearchProduct;

use App\Entity\Category;
use App\Entity\Color;
use App\Form\Object\SearchProduct;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('min_price', IntegerType::class,[
                'label' => 'Product`s minimal price',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('max_price', IntegerType::class,[
                'label' => 'Product`s maximum price',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('category', EntityType::class,[
                'label' => 'Product`s category',
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('color', EntityType::class, [
                'label' => 'Product`s color',
                'class' => Color::class,
                'choice_label' => 'color',
                'required' => false,
            ])
            ->add('min_weight', IntegerType::class,[
                'label' => 'Product`s minimal weight',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('max_weight', IntegerType::class,[
                'label' => 'Product`s maximum weight',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('substring', TextType::class,[
                'label' => 'Search by part/whole title/description',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('sort', ChoiceType::class,[
                'label' => 'Sort products by',
                'choices'=>[
                    '' => null,
                    'price' => 'p.price',
                    'weight' => 'u.weight'
                ],
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Search products'
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