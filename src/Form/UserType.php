<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($builder->create('employe', FormType::class, ['by_reference' => false])
                ->add('nom', TextType::class, ['label' => 'Nom'])
                ->add('prenom', TextType::class, ['label' => 'Prénom'])
                ->add('email', EmailType::class, ['label' => 'E-mail'])
                ->add('dateArrivee', HiddenType::class, [
                    'data' => 'now',
                ])
            )
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('password_confirm', PasswordType::class, ['label' => 'Confirmez le mot de passe'])
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
