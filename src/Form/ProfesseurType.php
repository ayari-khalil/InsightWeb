<?php

namespace App\Form;

use App\Entity\Ecole;
use App\Entity\Professeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2]),
                new Regex([
                    'pattern' => '/^[a-zA-Z\s]+$/',
                    'message' => 'Le nom ne doit contenir que des lettres et espaces.'
                ])
            ]
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2]),
                new Regex([
                    'pattern' => '/^[a-zA-Z\s]+$/',
                    'message' => 'Le prénom ne doit contenir que des lettres et espaces.'
                ])
            ]
        ])
        ->add('num_tel', IntegerType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 8, 'max' => 8]),
                new Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'Le numéro de téléphone doit contenir uniquement des chiffres.'
                ])
            ]
        ])
        ->add('adresse', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2]),
                new Regex([
                    'pattern' => '/^[a-zA-Z0-9\s]+$/',
                    'message' => 'L\'adresse ne doit contenir que des lettres, des chiffres et espaces.'
                ])
            ]
        ])
        ->add('ecole', EntityType::class, [
            'class' => Ecole::class, // Spécifiez la classe de votre entité Ecole
            'choice_label' => 'id', // ou tout autre attribut d'Ecole que vous souhaitez afficher
            'label' => 'École',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professeur::class,
        ]);
    }
}
