<?php

namespace App\Form\Product;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\ProductAttribute;
use App\Form\CategoryType\CategoryType;
use App\Form\Object\ProductJson;
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

class ProductJsonType extends AbstractType
{
    public function __construct(
    ){}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jsonProduct', FileType::class, [
                'label' => 'product_create_json.json_products',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/json',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JSON file',
                    ]),
                ]
            ])
//            ->add('pathToJsonProduct', TextType::class, [
//                'label' => 'product_create_json.json_products',
////                'mapped' => false,
//                'required' => true,
//            ])
            ->add('download', SubmitType::class, [
                'label' => 'product_create_json.download',
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductJson::class,
        ]);
    }
}