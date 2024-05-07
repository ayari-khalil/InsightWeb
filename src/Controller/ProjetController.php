<?php
namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;//importer la classe client depuis le SDK Twilio
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



#[Route('/projet')]
class ProjetController extends AbstractController
{
   
    #[Route('/', name: 'app_projet_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projets = $entityManager
            ->getRepository(Projet::class)
            ->findAll();
        // Collecter les données pour le graphique
        $projectsByCompany = [];
        foreach ($projets as $projet) {
            $companyName = $projet->getNomentreprise();
            if (!isset($projectsByCompany[$companyName])) {
                $projectsByCompany[$companyName] = 1;
            } else {
                $projectsByCompany[$companyName]++;
            }
        }

        

        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
            'projectsByCompany' => $projectsByCompany,
        ]);
    }

    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           

            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{idprojet}', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/{idprojet}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{idprojet}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getIdprojet(), $request->request->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idprojet}/send-sms', name: 'app_projet_send_sms', methods: ['GET'])]
    public function sendSms($idprojet, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les informations du projet
        $projet = $entityManager->getRepository(Projet::class)->find($idprojet);
        
        // Récupérer les identifiants Twilio à partir des paramètres de l'application
        $twilioAccountSid = $this->getParameter('twilio_account_sid');
        $twilioAuthToken  =  $this->getParameter('twilio_auth_token');
        $twilioPhoneNumber = $this->getParameter('twilio_phone_number');

        // Ajouter le code pays au numéro de téléphone du projet si nécessaire
        $phoneNumber = $projet->getNumTel(); 
        $phoneNumber = '+216' . $phoneNumber; 


        // Créer une instance du client Twilio
        $twilioClient = new Client($twilioAccountSid, $twilioAuthToken);

        // Envoyer le SMS avec Twilio
        $message = 'Vous êtes affecté à encadrer un projet spécifique dans une société spécifique dans un domaine spécifique.';
        $twilioClient->messages->create(
            $phoneNumber, // Utilisez le numéro de téléphone du projet comme destinataire
            [
                'from' => $twilioPhoneNumber,
                'body' => $message
            ]
        );
        // Rediriger vers la page précédente ou une autre page
        return $this->redirectToRoute('app_projet_index');
    }
    #[Route('/search', name: 'app_projet_search', methods: ['GET'])]
    public function search(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $term = $request->query->get('term');
        $sortField = $request->query->get('sortField', 'idprojet'); // Champ de tri par défaut
        $sortOrder = $request->query->get('sortOrder', 'ASC'); // Ordre de tri par défaut
    
        // Effectuer la recherche dans la base de données avec le terme de recherche et le tri
        $projects = $entityManager->getRepository(Projet::class)->search($term, $sortField, $sortOrder);
    
        // Convertir les résultats en tableau pour la réponse JSON
        $projectArray = [];
        foreach ($projects as $project) {
            $projectArray[] = [
                'id' => $project->getIdprojet(),
                'nomprojet' => $project->getNomprojet(),
                'description' => $project->getDescription(),
                // Ajouter d'autres champs si nécessaire
            ];
        }   
    
        // Renvoyer les résultats au format JSON
        return new JsonResponse($projectArray);
    }



#[Route('/{idprojet}/send-email', name: 'app_projet_send_email', methods: ['GET'])]
public function sendEmail($idprojet, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
{
    // Récupérer les informations du projet
    $projet = $entityManager->getRepository(Projet::class)->find($idprojet);

    // Récupérer l'adresse e-mail du destinataire
    $recipientEmail = 'oumeyma.benkram@esprit.tn';

    // Créer le contenu de l'e-mail
    $email = (new Email())
        ->from('yosr.mekki@esprit.tn') // Adresse e-mail de l'expéditeur
        ->to($recipientEmail) // Adresse e-mail du destinataire
        ->subject('bonjour Professeur! Nous vous informe que  etes affecté a encadrer des etudients qui appartient a INSIGHT ') // Sujet de l'e-mail
        ->text('Contenu du message'); // Contenu textuel de l'e-mail

    // Envoyer l'e-mail
    $mailer->send($email);

    // Rediriger vers la page précédente ou une autre page
    return $this->redirectToRoute('app_projet_index');
}

    
}