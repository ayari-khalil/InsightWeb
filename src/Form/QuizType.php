<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', null, [
                'constraints' => [
                    new NotBlank(), 
                ],
            ])
            ->add('opt1', null, [
                'constraints' => [
                    new NotBlank(), 
                    new Length(['min' => 1, 'max' => 255]),
                    new Regex('/^[a-zA-Z0-9\s]+$/'),
                ],
            ])
            ->add('opt2', null, [
                'constraints' => [
                    new NotBlank(), 
                    new Length(['min' => 1, 'max' => 255]), 
                    new Regex('/^[a-zA-Z0-9\s]+$/'), 
                ],
            ])
            ->add('opt3', null, [
                'constraints' => [
                    new NotBlank(), 
                    new Length(['min' => 1, 'max' => 255]), 
                    new Regex('/^[a-zA-Z0-9\s]+$/'),
                ],
            ])
            ->add('opt4', null, [
                'constraints' => [
                    new NotBlank(), 
                    new Length(['min' => 1, 'max' => 255]), 
                    new Regex('/^[a-zA-Z0-9\s]+$/'), 
                ],
            ])
            ->add('correct_option', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'quiz',
            
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
