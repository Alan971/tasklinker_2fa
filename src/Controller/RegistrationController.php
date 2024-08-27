<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Employe;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $employe = new Employe();

        $form = $this->createForm(RegistrationFormType::class, ['user' => $user, 'employe' => $employe]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // if passwords are same
            if ($form->get('plainPassword') === $form->get('password_confirm')) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->persist($employe);
                $entityManager->flush();


                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_projets');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
