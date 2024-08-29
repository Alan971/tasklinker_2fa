<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // if($this->isGranted('IS_AUTHENTICATED')) {
        //     return $this->redirectToRoute('app_projets');
        // }
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_dispatch');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }




    // Fonction de travail : sert a modifier la base de donnée user
    #[Route(path: '/add_data', name: 'app_add_data')]
    public function addData(EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $user->setRoles(array('ROLE_USER'));
            // $user->setPassword($userPasswordHasher->hashPassword(
            //     $user,
            //     'test'
            // ));
            // $em->persist($user);
        }
        $em->flush();

        return $this->redirectToRoute('app_login');
    }
}
