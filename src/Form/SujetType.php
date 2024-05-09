<?php

namespace App\Form;

use App\Entity\Sujet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class SujetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('domaine', ChoiceType::class, [
                'choices' => [
            
                    'Développement Web' => 'Développement Web',
                    'Intelligence Artificielle' => 'Intelligence Artificielle',
                    'Robotique' => 'Robotique',
                    
                'Développement Mobile' => 'Développement Mobile',
                'Sécurité Informatique' => 'Sécurité Informatique',
                'Cloud Computing' => 'Cloud Computing',
                'Analyse de Données' => 'Analyse de Données',
                ],
                'required' => true,
                'placeholder' => 'Sélectionner un domaine',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
        ]);
    }
}
