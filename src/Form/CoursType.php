<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Formation;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('formation', EntityType::class, [
                'class' => Formation::class, // Spécifiez l'entité Formation ici
                'choice_label' => 'nom', // Le champ de l'entité Formation à afficher dans le formulaire
                'placeholder' => 'Sélectionnez une formation', // Texte par défaut pour le champ
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier', // Étiquette du champ
                'required' => false, // Le champ n'est pas requis
                'attr' => [
                    'class' => 'form-control form-control-lg', // Classes Bootstrap pour le style
                    'id' => 'formFileLg', // ID du champ
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
