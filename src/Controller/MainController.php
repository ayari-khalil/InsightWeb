<?php

namespace App\Controller;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\FormationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class MainController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
            'current_page' => 'home', // Définissez la page actuelle comme 'home'
        ]);
    }
    

  
    /********************************************Afficher********************************************************* */
    #[Route('/Formation/read', name: 'read')]
    public function read(FormationRepository $formationRepo): Response
    {
        $formations = $formationRepo->findAll(); 
        return $this->render('read.html.twig', [
            'formations' => $formations, 
        ]);
    }
/**************************************Ajouter******************************************************************* */
    
    #[Route('/add', name:"add")]
    public function add(ManagerRegistry $doctrine , Request $request): Response{
        $em = $doctrine->getManager();

        $formation = new Formation();

        $form= $this->createForm(FormationType::class, $formation);
        $form->add('Ajouter',SubmitType::class);
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
            {
                 $em->persist($formation);
                 $em->flush();
                 return $this->redirectToRoute("read");
            } 
            else {
                return $this->renderForm("create.html.twig", ["form" => $form ,]);
             
                }
}
 /********************************************Supprimer********************************************************** */
#[Route('formation/delete/{id}', name: 'formation_delete')]
public function delete(ManagerRegistry $doctrine, $id, FormationRepository $formationRepo): Response
{
    $em = $doctrine->getManager();

    $formationDel = $formationRepo->find($id);

    if ($formationDel) {
        $em->remove($formationDel);
        $em->flush();
    }

    return $this->redirectToRoute('read'); 
}

    /********************************************Modifier********************************************************* */

    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(ManagerRegistry $doctrine, FormationRepository $repository, $id, Request $request)
    {
        $em = $doctrine->getManager();

        $foramation = $repository->find($id);

        $form = $this->createForm(FormationType::class, $foramation);
        $form->add('Edit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
             
            return $this->redirectToRoute("read");
        }

        return $this->render('edit.html.twig', [ 'form' => $form->createView(), ]);
    }
}
