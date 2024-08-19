<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('csv_file', FileType::class, [
            'label' => 'CSV File',
            'mapped' => false,
            'required' => true,
            'attr' => [
                'class' => 'form-control border-formcontrol',
                'id' => 'csvfile'
            ],
            'row_attr' => ['class' => 'm-t-20'],
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'text/csv',
                        'text/plain',
                        'application/vnd.ms-excel',
                    ],
                    'maxSizeMessage' => 'The uploaded file was too large. Please try to upload a smaller file.',
                    'mimeTypesMessage' => 'Please upload a valid CSV file',
                ])
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
