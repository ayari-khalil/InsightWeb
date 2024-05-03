<?php

namespace App\Form;

use App\Entity\Professeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'form-control mb-3'],
                
            ])
            ->add('firstName', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('lastName', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('birthDate', null, [
                'attr' => ['class' => 'form-control mb-3'],
                'widget' => 'single_text',
            ])
            ->add('phoneNumber', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('cin', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('password', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professeur::class,
        ]);
    }
}
