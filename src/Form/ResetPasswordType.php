<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newPassword', PasswordType::class, [
                'label' => 'New Password',
                'required' => true,
                // Add any additional options you need
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
                'required' => true,
                // Add any additional options you need
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
