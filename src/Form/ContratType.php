<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Ecole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_contrat', DateType::class, [
                'label' => 'Date Contrat',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datepicker',
                ],
                'constraints' => [
                    new NotBlank(), 
                ],
            ])
            ->add('nb_days', IntegerType::class, [
                'label' => 'Number of Days',
                'constraints' => [
                    new NotBlank(),
                    new Type([
                        'type' => 'integer',
                        'message' => 'The value {{ value }} is not a valid {{ type }}.',
                    ]),
                ],
            ])
            ->add('ecole', EntityType::class, [
                'class' => Ecole::class,
                'choice_label' => 'id', // ou tout autre attribut d'Ecole que vous souhaitez afficher
                'label' => 'École',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}