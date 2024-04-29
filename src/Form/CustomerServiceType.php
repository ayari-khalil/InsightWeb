<?php

namespace App\Form;

use App\Entity\CustomerService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CustomerServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your full name.']),
                    new Assert\Length(['max' => 255]),
                ],
                'attr' => [
                    'class' => 'form-control border-top-0 border-right-0 border-left-0 p-0',
                    'placeholder' => 'Your Name',
                    'label' => false,
                ],
            ])
            ->add('emailsup', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your email address.']),
                    new Assert\Email(['message' => 'Please enter a valid email address.']),
                ],
                'attr' => [
                    'class' => 'form-control border-top-0 border-right-0 border-left-0 p-0',
                    'placeholder' => 'Your Email',
                    'label' => false,
                ],
            ])
            ->add('pnsup', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your phone number.']),
                    new Assert\Length(['min' => 8, 'max' => 8, 'exactMessage' => 'Phone number must be 8 digits.']),
                ],
                'attr' => [
                    'class' => 'form-control border-top-0 border-right-0 border-left-0 p-0',
                    'placeholder' => 'Phone Number',
                    'label' => false,
                ],
            ])
            ->add('issue', ChoiceType::class, [
                'choices' => [
                    'Lost Progress' => 'Lost Progress',
                    'No Verification Code' => 'No Verification Code',
                    'Other' => 'Other',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please select an issue.']),
                ],
            ])
            ->add('subject', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter a subject.']),
                    new Assert\Length(['max' => 255]),
                ],
                'attr' => [
                    'class' => 'form-control border-top-0 border-right-0 border-left-0 p-0',
                    'placeholder' => 'Subject',
                    'label' => false,
                ],
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your message.']),
                ],
                'attr' => [
                    'placeholder' => 'Write your message here...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerService::class,
        ]);
    }
}
