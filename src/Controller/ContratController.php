<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/contrat')]
class ContratController extends AbstractController
{
    #[Route('/', name: 'app_contrat_index', methods: ['GET'])]
    public function index(Request $request, ContratRepository $contratRepository): Response
    {
        $sortBy = $request->query->get('sortBy', 'dateContrat'); // Par défaut, trier par date de contrat
    
        // Vérifiez la valeur sélectionnée dans le menu déroulant et définissez la propriété de tri en conséquence
        switch ($sortBy) {
            case 'dateContrat':
                $sortByProperty = 'dateContrat';
                break;
            case 'id':
                $sortByProperty = 'id';
                break;
            case 'ecole':
                $sortByProperty = 'ecole';
                break;
            default:
                $sortByProperty = 'dateContrat'; // Par défaut, trier par date de contrat
        }
    
        $contrats = $contratRepository->findAllSortedBy($sortByProperty);
    
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contrats,
        ]);
    }
    

    #[Route('/new', name: 'app_contrat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contrat);
            $entityManager->flush();

            return $this->redirectToRoute('app_contrat_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/listContrat', name: 'app_contrat_list', methods: ['GET'])]
public function showContratList(ContratRepository $contratRepository): Response
{
    $contrats = $contratRepository->findAll();
    
    return $this->render('contrat/ContratList.twig', [
        'contrats' => $contrats,
    ]);
}


    #[Route('/{id}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contrat_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_delete', methods: ['DELETE'])]
public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
        $entityManager->remove($contrat);
        $entityManager->flush();
        return $this->json(['message' => 'Contrat deleted successfully'], Response::HTTP_OK);
    }


    return $this->json(['message' => 'Failed to delete contrat'], Response::HTTP_BAD_REQUEST);
}


#[Route('/generatePdf/{id}', name: 'generatePdf' , methods: ['GET'])]
    public function generatePdf($id, ContratRepository $contratRepo): Response
    {
        $contrat = $contratRepo->find($id);
    
        if (!$contrat) {
            throw $this->createNotFoundException('contrat non trouvé avec l\'ID : ' . $id);
        }
    
        // Créer une instance Dompdf avec des options
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
    
        $dompdf = new Dompdf($pdfOptions);
    
        // Générer le contenu HTML à partir du modèle Twig
        $html = $this->renderView('contrat/pdf.html.twig', [
            'contrat' => $contrat,
        ]);
    
        // Charger le contenu HTML dans Dompdf et générer le PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
    
        // Générer le contenu PDF
        $dompdf->render();
    
        // Obtenir le contenu du PDF généré
        $pdfContent = $dompdf->output();
    
        // Créer une réponse HTTP avec le contenu du PDF
        $response = new Response($pdfContent);
    
        // Rendre le PDF téléchargeable en définissant les en-têtes HTTP appropriés
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="contrat.pdf"');
    
        // Retourner la réponse HTTP avec le PDF généré
        return $response;
    }


    

}
