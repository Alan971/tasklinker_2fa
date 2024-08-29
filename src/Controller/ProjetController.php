<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjetRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Projet;
use App\Form\ProjetType;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProjetController extends AbstractController
{
    public function __construct(
        private ProjetRepository $projetRepository,
        private StatutRepository $statutRepository,
        private EntityManagerInterface $entityManager,
    )
    {

    }
    
    /*  
        Liste de tous les projets
        Accessible à tous les utilisateurs connectés
    */  
    #[isGranted('ROLE_USER')]
    #[Route('/', name: 'app_projets')]
    public function projets(): Response
    {
        $projets = $this->projetRepository->findBy([
            'archive' => false,
        ]);

        return $this->render('projet/liste.html.twig', [
            'projets' => $projets,
        ]);
    }
    /*  
        L'Ajout d'un projet
        Accessible à tous les chefs de projet
    */
    #[isGranted('ROLE_ADMIN')]
    #[Route('/projets/ajouter', name: 'app_projet_add')]
    public function ajouterProjet(Request $request): Response
    {  
        $projet = new Projet();

        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $projet->setArchive(false);
            $this->entityManager->persist($projet);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_projet', ['id' => $projet->getId()]);
        }


        return $this->render('projet/nouveau.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /*  
        L'Édition d'un projet
        Accessible à tous les chefs de projet
    */
    #[isGranted('ROLE_USER')]
    #[Route('/projets/{id}', name: 'app_projet')]
    public function projet(int $id): Response
    {  
        $statuts = $this->statutRepository->findAll();
        $projet = $this->projetRepository->find($id);

        if(!$projet || $projet->isArchive()) {
            return $this->redirectToRoute('app_projets');
        }
        // contrôle d'accès au projet
        if (!$this->controleAccesProjet($projet->getEmployes(), $this->getUser()->getUserIdentifier())) {   
            return $this->redirectToRoute('app_projets');
        }

        return $this->render('projet/projet.html.twig', [
            'projet' => $projet,
            'statuts' => $statuts,
        ]);
    }
    /*  
        Archivage d'un projet
        Accessible à tous les chefs de projet
    */
    #[isGranted('ROLE_ADMIN')]
    #[Route('/projets/{id}/archiver', name: 'app_projet_archive')]
    public function archiverProjet(int $id): Response
    {  
        $projet = $this->projetRepository->find($id);

        if(!$projet || $projet->isArchive()) {
            return $this->redirectToRoute('app_projets');
        }

        $projet->setArchive(true);
        $this->entityManager->flush();
        
        return $this->redirectToRoute('app_projets');
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/projets/{id}/editer', name: 'app_projet_edit')]
    public function editerProjet(int $id, Request $request): Response
    {  
        $projet = $this->projetRepository->find($id);

        if(!$projet || $projet->isArchive()) {
            return $this->redirectToRoute('app_projets');
        }
        // contrôle d'accès au projet
        if (!$this->controleAccesProjet($projet->getEmployes(), $this->getUser()->getUserIdentifier())) {   
            return $this->redirectToRoute('app_projets');
        }

        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $projet->setArchive(false);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_projet', ['id' => $projet->getId()]);
        }


        return $this->render('projet/editer.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),

        ]);
    }
    // contrôle d'accès au projet
    // si l'utilisateur courant ne fait pas parti du projet : pas d'acces
    private function controleAccesProjet ($employes, $currentUser) : int    {
        $flag = 0;
        foreach($employes as $employe) {
            if ($employe->getEmail() === $currentUser) {
                $flag = 1;
            }
        }
    return $flag;
    }
}
