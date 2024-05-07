<?php

namespace App\Controller;

use App\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class FrontendController extends AbstractController
{
    #[Route('/frontend', name: 'frontend_index', methods: ['GET'])]
    public function frontIndex(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $entityManager
            ->getRepository(Projet::class)
            ->createQueryBuilder('p')
            ->getQuery();
    
        $projets = $paginator->paginate(
            $query, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page
            6 // Nombre d'éléments par page
        );
    
        return $this->render('frontend/frontIndex.html.twig', [
            'projets' => $projets,
        ]);
    }

#[Route('/{idprojet}', name: 'frontend_show', methods: ['GET'])]
public function show(Projet $projet): Response
{
    return $this->render('frontend/frontShow.html.twig', [
        'projet' => $projet,
    ]);
}
}