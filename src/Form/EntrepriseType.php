<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
$builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Le nom d\'entreprise ne doit contenir que des lettres et des espaces.',
                    ]),
                ],
            ])
            ->add('adresse', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('num_tel', null, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[1-9][0-9]{7}$/',
                        'message' => 'Le numéro de téléphone doit être composé de 8 chiffres et commencer par un chiffre entre 1 et 9.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
