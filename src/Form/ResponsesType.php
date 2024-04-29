<?php

namespace App\Form;

use App\Entity\Responses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ResponsesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idsup', null, [
                'label' => 'ID :',
                'attr' => ['class' => 'form-control rounded'],
            ])
            ->add('emailsup', null, [
                'label' => 'Email :',
                'attr' => ['class' => 'form-control rounded'],
            ])
            ->add('reponse', TextareaType::class, [ 
                'label' => 'Response:',
                'attr' => ['class' => 'form-control rounded', 'rows' => 10], 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Responses::class,
        ]);
    }
}
