<?php

namespace App\Form;

use App\Entity\Telephone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelephoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('phoneNumber');
//        ->add('phoneNumber', EntityType::class, [
//        'label' => 'Product`s phone',
//        'class' => Telephone::class,
//        'choice_label' => 'phoneNumber',
////            'required' => false,
//    ])
//        ->add('phoneNumber', EntityType::class, [
//            'label' => 'Product`s phone',
//            'class' => Telephone::class,
//            'choice_label' => 'phoneNumber',
////            'required' => false,
//        ])
//        ->add('phoneNumber', EntityType::class, [
//            'label' => 'Product`s phone',
//            'class' => Telephone::class,
//            'choice_label' => 'phoneNumber',
////            'required' => false,
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Telephone::class,
        ]);
    }
}