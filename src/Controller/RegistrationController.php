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
            $user->setEmploye($formUser->getData()->get('EmployeShortType'));
            //enregistrement des données non demandées :
            // $employe = new Employe();
            // //$employe->setNom($formUser->getData()->get('nom'));
            // // $employe->setNom($formUser->getData()['nom']);
            // $employe->setPrenom($formUser->get('nom')->getData());
            // $employe->setPrenom($formUser->get('prenom')->getData());
            // $employe->setEmail($formUser->get('email')->getData());
            // $employe->setDateArrivee($now);
            // $employe->setStatut('CDI');
            $entityManager->persist($user);
            // $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('app_projets');
            }
        return $this->render('registration/register.html.twig', [
            'formUser' => $formUser,
        ]);
    }
}
//Pasdemot6compliqué!