<?php

declare(strict_types=1);

namespace FrankProjects\UltimateWarfare\Form;

use FrankProjects\UltimateWarfare\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'label.name'
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'label.email'
                ]
            )
            ->add(
                'subject',
                TextType::class,
                [
                    'label' => 'label.subject'
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'label' => 'label.message'
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'label.send'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Contact::class,
                'translation_domain' => 'contact'
            ]
        );
    }
}
