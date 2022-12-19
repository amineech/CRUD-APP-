<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['autocomplete' => 'off' ],
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => ['autocomplete' => 'off' ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Nom d\'utilisateur requis'
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Au moins 8 caractères',
                        'max' => 30,
                        'maxMessage' => 'Au max 30 caractères'
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'attr' => ['autocomplete' => 'off'],
                'invalid_message' => 'Mots de passes sont pas identiques',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Mot de passe requis',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Conditions d\'utilisations',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Acceptez les conditions d\'utilisation.',
                    ]),
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => 'S\'inscrire',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
