<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Employe;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

use function PHPUnit\Framework\isEmpty;

class RegistrationController extends AbstractController
{
    #[Route('/dispatch', name: 'app_dispatch')]
    public function dispatch(): Response
    {
        return $this->render('security/dispatch.html.twig');
    }


    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $employe = new Employe();
        $now = new \DateTime('now');

        // create form for employe and user registration
        $formEmploye = $this->createForm(RegistrationFormType::class, $employe);
        $formUser = $this->createForm(UserType::class, $user);

        $formEmploye->handleRequest($request);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formEmploye->isSubmitted() && $formUser->isValid() && $formEmploye->isValid()) {
            // if passwords are same
            if ($formUser->get('plainPassword') === $formUser->get('password_confirm')) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $formUser->get('plainPassword')->getData()
                    )
                );
                $employe->setDateArrivee($now);
                $entityManager->persist($user);
                $entityManager->persist($employe);
                $entityManager->flush();

                return $this->redirectToRoute('app_projets');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $formEmploye,
            'formUser' => $formUser,
        ]);
    }
}
