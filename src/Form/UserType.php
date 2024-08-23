<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
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
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('email', EmailType::class, ['label' => 'E-mail'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('password_confirm', PasswordType::class, ['label' => 'Confirmez le mot de passe'])
            ->add('roles', HiddenType::class, [
                'default' => 'ROLE_USER'])
            ->add('dateArrivee', HiddenType::class, [
                'data' => 'now',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
