<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry; // Importez la classe ManagerRegistry
use App\Entity\Cours;
use App\Repository\CoursRepository;
use App\Form\CoursType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CoursController extends AbstractController
{
    #[Route('/home', name: 'app_cours')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    #[Route('/Cours/readCours', name: 'readCours')]
    public function read(CoursRepository $coursRepo): Response
    {
        $cours = $coursRepo->findAll(); 
        return $this->render('cours/readC.html.twig', [
            'cours' => $cours, 
        ]);
    }

    #[Route('/addCours', name: 'addCours')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
    
        $cours = new Cours();
    
        // Ajoutez le code pour accéder à la propriété "nom"
        $nom = $cours->getNom();
    
        $form = $this->createForm(CoursType::class, $cours);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cours);
            $em->flush();
            return $this->redirectToRoute('readCours'); // Utilisez 'readCours' comme nom de route
        } else {
            return $this->renderForm('cours/createC.html.twig',  ['form' => $form]);
        }
    
}




    #[Route('/deleteCours/{ref}', name: 'deleteCours')]
    public function delete(ManagerRegistry $doctrine, int $ref): Response
    {
        $em = $doctrine->getManager();
        $cours = $em->getRepository(Cours::class)->find($ref);
    
        if (!$cours) {
            throw $this->createNotFoundException('Le cours avec l\'identifiant ' . $ref . ' n\'existe pas.');
        }
    
        $em->remove($cours);
        $em->flush();
    
        return $this->redirectToRoute('readCours'); // Utilisez 'readCours' comme nom de route
    }
    #[Route('/editCours/{ref}', name: 'editCours')]
public function edit(ManagerRegistry $doctrine, CoursRepository $repository, $ref, Request $request)
{
    $em = $doctrine->getManager();

    $cours = $repository->find($ref); // Recherche du cours par le champ 'ref'
    $form = $this->createForm(CoursType::class, $cours);
        $form->add('Edit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
             
            return $this->redirectToRoute("readCours");
        }

        return $this->render('cours/editC.html.twig', [ 'form' => $form->createView(), ]);
    }

}
