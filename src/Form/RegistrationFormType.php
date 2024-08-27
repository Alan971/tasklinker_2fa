<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($builder->create('employe', FormType::class, ['by_reference' =>false])
                ->add('nom', TextType::class, ['label' => 'Nom'])
                ->add('prenom', TextType::class, ['label' => 'Prénom'])
                ->add('email', EmailType::class, ['label' => 'E-mail'])
                ->add('dateArrivee', HiddenType::class, [
                    'data' => 'now',
                ])
            )
            // ajout d'un builder inclus dans le premier.
            // pour implémenter la table user
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('password_confirm', PasswordType::class, [
                'label' => 'Confirmez le mot de passe',
                'mapped' => false
            ])
            ->add('roles', HiddenType::class, [
                'data' => 'ROLE_USER'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
