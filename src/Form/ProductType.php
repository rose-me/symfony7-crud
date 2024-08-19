<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control border-formcontrol',
                    'id' => 'name'
                ],
                'row_attr' => ['class' => 'm-t-20'],                
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control border-formcontrol',
                    'id' => 'description'
                ],
                'row_attr' => ['class' => 'm-t-20'],
            ])
            ->add('price', MoneyType::class, [
                'required' => false,
                'label' => 'Price',
                'attr' => [
                    'class' => 'form-control border-formcontrol',
                    'id' => 'price'
                ],
                'row_attr' => ['class' => 'm-t-20'],
            ])
            ->add('stockQuantity', IntegerType::class, [
                'required' => false,
                'label' => 'Stock Quantity',
                'attr' => [
                    'class' => 'form-control border-formcontrol',
                    'id' => 'stockQuantity'
                ],
                'row_attr' => ['class' => 'm-t-20'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
