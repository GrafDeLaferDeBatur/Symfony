<?php

namespace App\Form\Product;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\ProductAttribute;
use App\Form\CategoryType\CategoryType;
use App\Form\ProductAttribute\ProductAttributeType;
use App\Form\TelephoneType;
use App\Service\User\AuthService;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

class ProductType extends AbstractType
{
    public function __construct(
//        private readonly AuthService $authService,
//        private readonly AuthenticationUtils $authenticationUtils,
    ){}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Product`s title'
            ])
            ->add('descr', TextareaType::class, [
                'label' => 'Product`s description',
                'help' => 'Write sth plz'
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Product`s price'
            ])
            ->add('amount', IntegerType::class, [
                'label' => 'Product`s amount'
            ])
            ->add('dimensions', ChoiceType::class, [
                'label' => 'Product`s dimensions',
                'choices' => [
                    '' => null,
                    'Small' => Product::DIMENSIONS_SMALL,
                    'Medium' => Product::DIMENSIONS_MEDIUM,
                    'Large' => Product::DIMENSIONS_LARGE
                ]
            ])
            ->add('color', EntityType::class, [
                'label' => 'Product`s color',
                'class' => Color::class,
                'choice_label' => 'color',
                'required' => false,
            ])
            ->add('productAttribute', ProductAttributeType::class)
            ->add('category', EntityType::class,[
                'label' => 'Product`s category',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('imageName', FileType::class, [
                'label' => 'Image of product',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG document',
                    ]),
                ]
            ])
            ->add('phoneNumber', CollectionType::class,[
                'entry_type' => TelephoneType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}