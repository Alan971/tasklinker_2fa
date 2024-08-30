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
        $now = new \DateTime('now');

        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formUser->get('password')->getData()
                )
            );
            // construction de l'objet employe
            $employe = new Employe();
            $employe = $formUser->get('employe')->getData();
            $employe->setEmail($formUser->get('email')->getData());
            $employe->setDateArrivee($now);
            $employe->setStatut('CDI');
            // chargement de l'objet employe dans user
            // qui va le transmettre via son setter
            $user->setEmploye($employe);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_projets');
            }
        return $this->render('registration/register.html.twig', [
            'formUser' => $formUser,
        ]);
    }
}
