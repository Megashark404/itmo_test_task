<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Наименование'])
            ->add('year', NumberType::class, ['label' => ' Год издания'])
            ->add('ISBN', TextType::class, ['label' => 'Номер ISBN'])
            ->add('pages', NumberType::class, ['label' => 'Количество страниц'])
            ->add('authors', EntityType::class, [
                'label' => 'Укажите авторов (можно несколько)',
                'class' => Author::class,
                'multiple' => true,
            ])
            ->add('cover_file_name', FileType::class, [
                'label' => 'Изображение обложки',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Выберите изображение в формате jpg или png',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Сохранить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
