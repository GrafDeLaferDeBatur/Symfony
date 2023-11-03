<?php

namespace App\Form\Product;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\ProductAttribute;
use App\Form\CategoryType\CategoryType;
use App\Form\DataTransformer\TagsToStringTransformer;
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
        private readonly TagsToStringTransformer $transformer,
    ){}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'translation_domain' => 'form',
                'label' => 'product_create.title'
            ])
            ->add('tags', TextType::class, [
//                'mapped' => false,
                'required' => false,
                'translation_domain' => 'form',
                'label' => 'product_create.tags'
            ])
            ->add('descr', TextareaType::class, [
                'translation_domain' => 'form',
                'label' => 'product_create.description',
//                'help' => 'Write sth plz'
            ])
            ->add('price', IntegerType::class, [
                'translation_domain' => 'form',
                'label' => 'product_create.price',
            ])
            ->add('amount', IntegerType::class, [
                'translation_domain' => 'form',
                'label' => 'product_create.amount'
            ])
            ->add('dimensions', ChoiceType::class, [
                'translation_domain' => 'form',
                'label' => 'product_create.dimensions',
                'choices' => [
                    '' => null,
                    'Small' => Product::DIMENSIONS_SMALL,
                    'Medium' => Product::DIMENSIONS_MEDIUM,
                    'Large' => Product::DIMENSIONS_LARGE
                ]
            ])
            ->add('color', EntityType::class, [
                'translation_domain' => 'form',
                'label' => 'product_create.color',
                'class' => Color::class,
                'choice_label' => 'color',
                'required' => false,
            ])
            ->add('productAttribute', ProductAttributeType::class,[
                'translation_domain' => 'form',
                'label' => 'product_create.attribute',
            ])
            ->add('category', EntityType::class,[
                'translation_domain' => 'form',
                'label' => 'product_create.category',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('phoneNumber', CollectionType::class,[
                'entry_type' => TelephoneType::class,
                'label' => false,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('imageName', FileType::class, [
                'translation_domain' => 'form',
                'label' => 'product_create.image',
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
            ->add('save', SubmitType::class, [
                'translation_domain' => 'form',
                'label' => 'product_create.save',
            ]);

        $builder->get('tags')
            ->addModelTransformer($this->transformer);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}