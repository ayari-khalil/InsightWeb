<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Professeur;
use App\Form\ProfesseurType;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/professeur')]
class ProfesseurController extends AbstractController
{
    #[Route('/', name: 'app_professeur_index', methods: ['GET'])]
    public function index(ProfesseurRepository $professeurRepository): Response
    {
        return $this->render('professeur/index.html.twig', [
            'professeurs' => $professeurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_professeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, SessionInterface $session): Response
{
    $professeur = new Professeur();
    $form = $this->createForm(ProfesseurType::class, $professeur);
    $form->handleRequest($request);
    $userId = $session->get('user')['id'];

    // Retrieve the user object from the database based on the user ID
    $user = $doctrine->getRepository(Admin::class)->find($userId);

    if ($form->isSubmitted() && $form->isValid()) {
        $professeur->setRole("professeur");
        // Check if email already exists
        $existingEmail = $entityManager->getRepository(Professeur::class)->findOneBy(['email' => $professeur->getEmail()]);
        if ($existingEmail) {
            $form->get('email')->addError(new FormError('This email is already in use.'));
            return $this->render('professeur/new.html.twig', [
                'professeur' => $professeur,
                'form' => $form->createView(),
                'user' => $user
            ]);
        }

        // Check if CIN already exists
        $existingCIN = $entityManager->getRepository(Professeur::class)->findOneBy(['cin' => $professeur->getCin()]);
        if ($existingCIN) {
            $form->get('cin')->addError(new FormError('This CIN is already in use.'));
            return $this->render('professeur/new.html.twig', [
                'professeur' => $professeur,
                'form' => $form->createView(),
                'user' => $user
            ]);
        }

        $entityManager->persist($professeur);
        $entityManager->flush();

        return $this->redirectToRoute('app_professeur_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('professeur/new.html.twig', [
        'professeur' => $professeur,
        'form' => $form->createView(),
        'user' => $user
    ]);
}

    #[Route('/{id}', name: 'app_professeur_show', methods: ['GET'])]
    public function show(Professeur $professeur): Response
    {
        return $this->render('professeur/show.html.twig', [
            'professeur' => $professeur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_professeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Professeur $professeur, EntityManagerInterface $entityManager,ManagerRegistry $doctrine,SessionInterface $session): Response
    {
        $form = $this->createForm(ProfesseurType::class, $professeur);
        $form->handleRequest($request);
        $userId = $session->get('user')['id'];

        // Retrieve the user object from the database based on the user ID
        $user = $doctrine->getRepository(Admin::class)->find($userId);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('professeurs', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professeur/edit.html.twig', [
            'professeur' => $professeur,
            'form' => $form->createView(),
            'user'=>$user
        ]);
    }

    #[Route('/{id}', name: 'app_professeur_delete', methods: ['POST'])]
    public function delete(Request $request, Professeur $professeur, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $submittedToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete'.$professeur->getId(), $submittedToken)) {
            $entityManager->remove($professeur);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('professeurs', [], Response::HTTP_SEE_OTHER);
    }
    
    
}
