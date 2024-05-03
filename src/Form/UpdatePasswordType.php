<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('currentPassword', TextType::class, [
            'label' => 'Current Password',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your current password',
                ]),
                new Callback([
                    'callback' => function ($value, ExecutionContextInterface $context) use ($options) {
                        // Custom validation callback to check if the current password matches
                        if ($value !== $options['user']->getPassword()) {
                            $context->buildViolation('Incorrect current password')
                                ->addViolation();
                        }
                    },
                ]),
            ],
        ])
        ->add('newPassword', PasswordType::class, [
            'label' => 'New Password',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a new password',
                ]),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Your password should be at least {{ limit }} characters long',
                ]),
            ],
        ]);
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        // Configure your form options here
        'user' => null,
    ]);

    // Add custom normalizer to handle the 'user' option
    $resolver->setNormalizer('user', function (Options $options, $value) {
        if (null === $value) {
            throw new \InvalidArgumentException('The "user" option must be provided.');
        }

        return $value;
    });
}

}
