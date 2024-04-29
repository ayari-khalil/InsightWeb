<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestPassingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Add form fields as needed
            ->add('answers', ChoiceType::class, [
                'label' => 'Select your answers:',
                'choices' => [
                    'Option 1' => 'Option 1',
                    'Option 2' => 'Option 2',
                    'Option 3' => 'Option 3',
                    'Option 4' => 'Option 4',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Define your form data class if needed
            //'data_class' => YourFormDataClass::class,
        ]);
    }
}
