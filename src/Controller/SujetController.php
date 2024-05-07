<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Form\SujetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sujet')]
class SujetController extends AbstractController
{
    #[Route('/', name: 'app_sujet_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sujets = $entityManager
            ->getRepository(Sujet::class)
            ->findAll();

        return $this->render('sujet/index.html.twig', [
            'sujets' => $sujets,
        ]);
    }

    #[Route('/new', name: 'app_sujet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sujet = new Sujet();
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sujet);
            $entityManager->flush();

            return $this->redirectToRoute('app_sujet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sujet/new.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    #[Route('/{idsujet}', name: 'app_sujet_show', methods: ['GET'])]
    public function show(Sujet $sujet): Response
    {
        return $this->render('sujet/show.html.twig', [
            'sujet' => $sujet,
        ]);
    }

    #[Route('/{idsujet}/edit', name: 'app_sujet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sujet $sujet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sujet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    #[Route('/{idsujet}', name: 'app_sujet_delete', methods: ['POST'])]
    public function delete(Request $request, Sujet $sujet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sujet->getIdsujet(), $request->request->get('_token'))) {
            $entityManager->remove($sujet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sujet_index', [], Response::HTTP_SEE_OTHER);
    }
}
