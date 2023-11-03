<?php

namespace App\Form\ProductAttribute;

use App\Entity\Color;
use App\Entity\Product;
use App\Entity\ProductAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ProductAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('width', TextType::class, [
                'label' => 'product_create.width',
                'constraints' => [new Valid()]
            ])
            ->add('weight', TextType::class, [
                'label' => 'product_create.weight',
                'constraints' => [new Valid()]
            ])
            ->add('length', TextType::class, [
                'label' => 'product_create.length',
                'constraints' => [new Valid()]
            ])
            ->add('height', TextType::class, [
                'label' => 'product_create.height',
                'constraints' => [new Valid()]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductAttribute::class,
        ]);
    }
}