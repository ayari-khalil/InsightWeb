<?php

namespace App\Controller;

use DateTime;
use App\Entity\Responses;
use App\Entity\CustomerService;
use App\Form\ResponsesType;
use App\Repository\ResponsesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Twilio\Rest\Client;




#[Route('/responses')]
class ResponsesController extends AbstractController
{
    #[Route('/', name: 'app_responses_index', methods: ['GET'])]
    public function index(Request $request, ResponsesRepository $responsesRepository, PaginatorInterface $paginator): Response
    {
        $allResponses = $responsesRepository->findAll();

        $responses = $paginator->paginate(
            $allResponses,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('responses/index.html.twig', [
            'responses' => $responses,
        ]);
    }
    


    #[Route('/new', name: 'app_responses_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $idsup = $request->request->get('idsup');
        $emailsup = $request->request->get('emailsup');
        
        $idsup = $idsup ? (int) $idsup : null;
        
        $response = new Responses();
        $response->setIdsup($idsup);
        $response->setEmailsup($emailsup);
        $response->setDater(new DateTime()); 
        
        $form = $this->createForm(ResponsesType::class, $response);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($response);
            $entityManager->flush();
            
            $customerService = null;
            if ($idsup) {
                $customerService = $entityManager->getRepository(CustomerService::class)->find($idsup);
                if (!$customerService) {
                    throw $this->createNotFoundException('CustomerService not found for id ' . $idsup);
                }
            }
            $pnsup = '+216' .  $customerService->getPnsup();

            if ($customerService && $customerService->getIssue() === "No Verification Code") {
                $verificationCode = $this->generateRandomCode();
                $this->sendVerificationCodeViaSMS($pnsup, $verificationCode);
            }
        
            $responseContent = $form->get('reponse')->getData();
            $htmlContent = $htmlContent = '<html>
            <head>
                <title>Response</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f2f2f2;
                        padding: 20px;
                    }
                    .container {
                        border: none;
                        border-radius: 10px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                        margin: 20px auto;
                        max-width: 600px;
                        background-color: #fff;
                    }
                    h1 {
                        color: #5c99fa;
                        text-align: center;
                    }
                    img {
                        display: block;
                        margin: 0 auto;
                        width: 100px;
                    }
                    hr {
                        border: 1px solid #5c99fa;
                    }
                    p {
                        color: #333;
                        font-size: 16px;
                    }
                    .footer {
                        background-color: #5c99fa;
                        color: #fff;
                        padding: 10px;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                <img src="https://i.ibb.co/cwxJtQ9/logo.png" alt="Logo">
                    <h1>Ticket Response</h1>
                    <hr>
                    <p>Hello! We are deeply sorry for the inconvenience.</p>
                    <p>' . $responseContent . '</p>
                    <p>Thank you for reaching out.</p>
                </div>
                <div class="footer">
                    <p>Insight: <a href="https://your-insight.com" style="color: #fff; text-decoration: underline;">Our Website</a></p>
                </div>
            </body>
        </html>
        ';
            
        
            $email = (new Email())
                ->from('Nadine.benabdallah@esprit.tn')
                ->to($emailsup)
                ->subject('Ticket Response')
                ->html($htmlContent);
        
            $mailer->send($email);
        
            if ($idsup) {
                $customerService = $entityManager->getRepository(CustomerService::class)->find($idsup);
                $customerService->setStater(2);
                $entityManager->flush();
            }
        
            return $this->redirectToRoute('app_responses_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('responses/new.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

    private function generateRandomCode(): string
    {
        $code = mt_rand(100000, 999999);
        return (string) $code;
    }


    private function sendVerificationCodeViaSMS(string $pnsup, string $verificationCode): void
    {
        $twilioSid = "AC99bd55ee524e7e9029705a38e6e9dd26";
        $twilioToken = "8772cd3bde4f55f9c3f88c6f38af6997";
        $twilioPhoneNumber =  "+14432750337";
        $client = new Client($twilioSid, $twilioToken);
        $smsContent = "Your verification code is: $verificationCode";


        $client->messages->create(
            $pnsup,
            [
                'from' => $twilioPhoneNumber,
                'body' => $smsContent 
            ]
        );
    }

    #[Route('/{idres}', name: 'app_responses_delete', methods: ['POST'])]
    public function delete(Request $request, Responses $response, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$response->getIdres(), $request->request->get('_token'))) {
            $entityManager->remove($response);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_responses_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/sort', name: 'app_response_sort', methods: ['GET'])]
    public function sort(Request $request, ResponsesRepository $responsesRepository): Response
    {
        $criteria = $request->query->get('criteria');
        $direction = $request->query->get('direction', 'asc');
        $orderBy = [];

        switch ($criteria) {
            case 'idsup':
                $orderBy = ['idsup' => $direction];
                break;
            case 'emailsup':
                $orderBy = ['emailsup' => $direction];
                break;
            case 'reponse':
                $orderBy = ['reponse' => $direction];
                break;
            default:
                $orderBy = ['idsup' => $direction];
        }
        $response = $responsesRepository->findBy([], $orderBy);

        return $this->render('responses/index.html.twig', [
            'responses' => $responses,
        ]);
    }
}
