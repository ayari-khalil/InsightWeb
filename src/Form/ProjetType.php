<?php

// src/Form/ProjetType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Projet;
use App\Entity\Sujet;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomprojet', null, [
            'constraints' => [
                new NotBlank(['message' => 'Le nom du projet ne peut pas être vide.']),
                new Regex([
                    'pattern' => '/^[^0-9].*$/',
                    'message' => 'Le nom du projet ne doit pas commencer par un chiffre.'
                ]),
            ],
        ])
        
            ->add('description', null, [
                'constraints' => [
                    new NotBlank(['message' => 'La description ne peut pas être vide.']),
                    new Regex(['pattern' => '/^[a-zA-Z\s]+$/', 'message' => 'La description ne doit pas contenir de chiffres.']),
                ],
            ])
            ->add('nomentreprise', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom de l\'entreprise ne peut pas être vide.']),
                    new Regex(['pattern' => '/^[a-zA-Z\s]+$/', 'message' => 'Le nom de l\'entreprise ne doit pas contenir de chiffres.']),
                ],
            ])
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse email ne peut pas être vide.']),
                    new Email(['message' => 'Veuillez saisir une adresse email valide.']),
                ],
            ])
         
            ->add('numTel', TextType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro de téléphone ne peut pas être vide.']),
                    new Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le numéro de téléphone doit être composé de 8 chiffres.'
                    ]),
                ],
            ])
            
            ->add('domaine', EntityType::class, [
                'class' => Sujet::class,
                'choice_label' => 'domaine',
                'constraints' => [
                    new NotBlank(['message' => 'Le domaine ne peut pas être vide.']),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
