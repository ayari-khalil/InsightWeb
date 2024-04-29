<?php
namespace App\Form;

use App\Entity\Tests;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class TestsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('duree', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Entrez la duration de test']),
                    new Type([
                        'type' => 'integer',
                        'message' => 'le valeur {{ value }} nest pas valide {{ type }} pour durée.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez durée en minutes',
                ],
                'label' => 'Duration (minutes)',
            ])
            ->add('note', IntegerType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'integer',
                        'message' => 'le valeur {{ value }} nest pas valide {{ type }} pour note.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez la note',
                ],
                'label' => 'Note',
            ])
            ->add('matiere', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter the subject']),
                    new Length([
                        'min' => 1,
                        'max' => 255,
                        'minMessage' => 'The subject must be at least {{ limit }} characters long',
                        'maxMessage' => 'The subject cannot be longer than {{ limit }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\s]+$/',
                        'message' => 'The subject can only contain letters, numbers, and spaces.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter the subject',
                ],
                'label' => 'Subject',
            ])
           
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'tets',
            
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tests::class,
        ]);
    }
}