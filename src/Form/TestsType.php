<?php

namespace App\Form;

use App\Entity\Tests;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class TestsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('duree', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Type([
                        'type' => 'integer',
                        'message' => 'The value {{ value }} is not a valid {{ type }}.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('note', IntegerType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'integer',
                        'message' => 'The value {{ value }} is not a valid {{ type }}.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ]) 
            ->add('matiere', null, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('questions', CollectionType::class, [
                'entry_type' => QuestionType::class, // Replace 'QuestionType' with your form type for questions
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true, 
                'allow_delete' => true, 
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tests::class,
        ]);
    }
}