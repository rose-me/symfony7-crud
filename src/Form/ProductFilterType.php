<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('priceMin', MoneyType::class, [
                'required' => false,
                'label' => 'Min Price',
                'attr' => [
                    'class' => 'form-control border-formcontrol',
                    'id' => 'priceMin'
                ],
                'row_attr' => ['class' => 'col-md-6'],
            ])
            ->add('priceMax', MoneyType::class, [
                'required' => false,
                'label' => 'Max Price',
                'attr' => [
                    'class' => 'form-control border-formcontrol',
                    'id' => 'priceMax'
                ],
                'row_attr' => ['class' => 'col-md-6'],
            ])
            ->add('stockQuantity', IntegerType::class, [
                'required' => false,
                'label' => 'Stock Quantity',
                'attr' => [
                    'class' => 'form-control border-formcontrol',
                    'id' => 'stockQuantity'
                ],
                'row_attr' => ['class' => 'col-md-6'],
            ])
            ->add('createdDate', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Created Date',
                'attr' => [
                    'class' => 'form-control border-formcontrol',
                    'id' => 'createdDate'
                ],
                'row_attr' => ['class' => 'col-md-6'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
