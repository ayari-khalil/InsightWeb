<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Diplome;
use App\Form\DiplomeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\DiplomeRepository;
use App\Repository\CoursRepository;
use App\Repository\FormationRepository;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class DiplomeController extends AbstractController
{
    #[Route('/home', name: 'app_diplome')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'DiplomeController',
        ]);
    }

    /**************************************Afficher********************************************************* */

    #[Route('/Diplome/read', name: 'readDiplome')]
    public function read(DiplomeRepository $diplomeRepo): Response
    {
        $diplome = $diplomeRepo->findAll();
        return $this->render('diplome/readD.html.twig', [
            'diplome' => $diplome,
        ]);
    }

    /**************************************Ajouter******************************************************************* */
    
    #[Route('/addDiplome', name: 'addDiplome')]
    public function add(Request $request, ManagerRegistry $registry): Response
    {
        $em = $registry->getManager();
    
        $diplome = new Diplome();
    
        $form = $this->createForm(DiplomeType::class, $diplome);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($diplome);
            $em->flush();
            return $this->redirectToRoute('readDiplome'); // Redirection à ajuster selon votre configuration de routes
        }
    
        // Passer directement le formulaire à la méthode render()
        return $this->render('diplome/createD.html.twig', ['form' => $form->createView()]);
    }

/********************************************Supprimer********************************************************** */


    #[Route('/diplome/delete/{id}', name: 'deleteDiplome')]
public function deleteDiplome(ManagerRegistry $doctrine, $id, DiplomeRepository $diplomeRepo): Response
{
    $em = $doctrine->getManager();

    $diplomeDel = $diplomeRepo->find($id);

    if ($diplomeDel) {
        $em->remove($diplomeDel);
        $em->flush();
    }

    return $this->redirectToRoute('readDiplome'); // Redirection vers la page de lecture des diplômes après suppression
}

    
  /********************************************Modifier********************************************************** */

#[Route('/editDiplome/{id}', name: 'editDiplome')]
    public function edit(Request $request, ManagerRegistry $doctrine, $id, DiplomeRepository $diplomeRepo): Response
    {
        $em = $doctrine->getManager();

        $diplome = $diplomeRepo->find($id);

        if (!$diplome) {
            throw $this->createNotFoundException('Diplome non trouvé avec l\'ID : ' . $id);
        }

        $form = $this->createForm(DiplomeType::class, $diplome);
        $form->add('Enregistrer', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('readDiplome');
        }

        return $this->render('diplome/editD.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/generatePdf/{id}', name: 'generatePdf')]
    public function generatePdf($id, DiplomeRepository $diplomeRepo): Response
    {
        $diplome = $diplomeRepo->find($id);
    
        if (!$diplome) {
            throw $this->createNotFoundException('Diplome non trouvé avec l\'ID : ' . $id);
        }
    
        // Créer une instance Dompdf avec des options
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
    
        $dompdf = new Dompdf($pdfOptions);
    
        // Générer le contenu HTML à partir du modèle Twig
        $html = $this->renderView('diplome/pdf.html.twig', [
            'diplome' => $diplome,
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
        $response->headers->set('Content-Disposition', 'attachment; filename="diplome.pdf"');
    
        // Retourner la réponse HTTP avec le PDF généré
        return $response;
    }
    
    #[Route('/generate-pdf-and-send-email/{id}', name: 'generate_pdf_and_send_email')]

    public function generatePdfAndSendEmail(Request $request, MailerInterface $mailer, $id,DiplomeRepository $diplomeRepo): Response
    {
        $diploma = $diplomeRepo->find($id);
            
    // Check if the diploma exists
    if (!$diploma) {
        throw $this->createNotFoundException('Diploma not found.');
    }

    // Generate the PDF content
    $pdfContent = $this->renderView('diplome/pdf.html.twig', [
        'diplome' => $diploma,
    ]);

    // Create PDF file
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($pdfContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfPath = 'diploma_' . $diploma->getId() . '.pdf'; // PDF file path

    // Save PDF filev
    file_put_contents($pdfPath, $dompdf->output());

    // Create and send email with PDF attachment
    $email = (new Email())
        ->from('ebtihel475@gmail.com')
        ->to($diploma->getEmail())
        ->subject('Diploma PDF')
        ->text('Please find the attached diploma PDF.')
        ->attachFromPath($pdfPath);

    $mailer->send($email);

    // Delete the temporary PDF file
    unlink($pdfPath);

    return new Response('Email with PDF attachment sent successfully.');
}




#[Route('/stat', name: 'app_diplome_stat')]
public function stat(
    DiplomeRepository $DiplomeRepository,
    CoursRepository $CoursRepository,
    FormationRepository $FormationRepository
): Response {
    // Récupérer le nombre total de livreurs
    $nombreDiplomes = count($DiplomeRepository->findAll());

    // Récupérer le nombre total de missions
    $nombreCours = count($CoursRepository->findAll());

    // Récupérer le nombre total de commandes
    $nombreFormations = count($FormationRepository->findAll());

    

    // Préparer les données pour le graphique
    $data = [
        'labels' => ['Diplomes', 'Cours', 'Formations'],
        'values' => [
            $nombreDiplomes,
            $nombreCours,
            $nombreFormations,
            
        ]
    ];

    return $this->render('diplome/stat.html.twig', [
        'data' => $data,
        'nombreDiplomes' => $nombreDiplomes,
        'nombreCours' => $nombreCours,
        'nombreFormations' => $nombreFormations,
        
    ]);
}
}
