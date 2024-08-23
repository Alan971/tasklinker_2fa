<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_new_user')]
    public function addNewUser(Request $request): Response
    {
        $user = new User;
        $employe = new Employe;

        $form1 = $this->createForm(UserType::class, $employe);
        $form1->handleRequest($request);
        $form2 = $this->createForm(UserType::class, $user);
        $form2->handleRequest($request);


        return $this->render('user/index.html.twig', [
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
        ]);
    }
}
