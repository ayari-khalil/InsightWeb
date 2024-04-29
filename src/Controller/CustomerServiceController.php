<?php

namespace App\Controller;

use App\Entity\CustomerService;
use App\Form\CustomerServiceType;
use App\Repository\CustomerServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/customer_service')]
class CustomerServiceController extends AbstractController
{
    #[Route('/', name: 'app_customer_service_index', methods: ['GET'])]
    public function index(CustomerServiceRepository $customerServiceRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $criteria = $request->query->get('criteria', 'idsup');
        $direction = $request->query->get('direction', 'asc');
    
        $queryBuilder = $customerServiceRepository->createQueryBuilder('cs')
            ->orderBy('cs.' . $criteria, $direction);
    
        $customerServices = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), 
            10
        );
    
        return $this->render('customer_service/index.html.twig', [
            'customer_services' => $customerServices,
            'criteria' => $criteria,
            'direction' => $direction,
        ]);
    }
    
    
    #[Route('/new', name: 'app_customer_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customerService = new CustomerService();
        $form = $this->createForm(CustomerServiceType::class, $customerService);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fullname = $form->get('fullname')->getData();
            $emailsup = $form->get('emailsup')->getData();
            $pnsup = $form->get('pnsup')->getData();
            $issue = $form->get('issue')->getData();
            $subject = $form->get('subject')->getData();
            $message = $form->get('message')->getData();

            $errors = [];
            if (empty($fullname)) {
                $errors[] = 'Fullname is required.';
            }
            if (empty($emailsup) || !filter_var($emailsup, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email is invalid or required.';
            }
            if (empty($pnsup) || !preg_match('/^\d{8}$/', $pnsup)) {
                $errors[] = 'Phone Number is invalid or required.';
            }
            if (empty($issue)) {
                $errors[] = 'Issue is required.';
            }
            if (empty($subject)) {
                $errors[] = 'Subject is required.';
            }
            if (empty($message)) {
                $errors[] = 'Message is required.';
            }

            if (empty($errors)) {
                $customerService->setFullname($fullname);
                $customerService->setEmailsup($emailsup);
                $customerService->setPnsup($pnsup);
                $customerService->setIssue($issue);
                $customerService->setSubject($subject);
                $customerService->setMessage($message);

                $entityManager->persist($customerService);
                $entityManager->flush();

                $this->addFlash('success', 'Thank you for reaching out, Support Team will get back to you soon');

                return $this->redirectToRoute('app_customer_service_show');
            } else {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
            }
        }

        return $this->render('customer_service/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/show', name: 'app_customer_service_show', methods: ['GET'])]
    public function show(CustomerServiceRepository $customerServiceRepository): Response
    {
        $customerService = $customerServiceRepository->findOneBy([], ['idsup' => 'DESC']);
    
        if (!$customerService) {
            throw $this->createNotFoundException('No reclamations found.');
        }
    
        return $this->render('customer_service/show.html.twig', [
            'customer_service' => $customerService,
        ]);
    }
    
    

    #[Route('/{idsup}/edit', name: 'app_customer_service_edit', methods: ['POST'])]
    public function edit(Request $request, CustomerService $customerService, EntityManagerInterface $entityManager): Response
    {
        $newStater = $customerService->getStater() == 0 ? 1 : 0;
        $customerService->setStater($newStater);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_customer_service_index');
    }
    
    

    #[Route('/{idsup}', name: 'app_customer_service_delete', methods: ['POST'])]
    public function delete(Request $request, CustomerService $customerService, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customerService->getIdsup(), $request->request->get('_token'))) {
            $entityManager->remove($customerService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_customer_service_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/sort', name: 'app_customer_service_sort', methods: ['GET'])]
    public function sort(Request $request, CustomerServiceRepository $customerServiceRepository): Response
    {
        $criteria = $request->query->get('criteria');
        $direction = $request->query->get('direction', 'asc'); 
        $orderBy = [];

        switch ($criteria) {
            case 'fullname':
                $orderBy = ['fullname' => $direction];
                break;
            case 'issue':
                $orderBy = ['issue' => $direction];
                break;
            case 'stater':
                $orderBy = ['stater' => $direction];
                break;
            default:
                $orderBy = ['idsup' => $direction];
        }

        $customerServices = $customerServiceRepository->findBy([], $orderBy);

        return $this->render('customer_service/index.html.twig', [
            'customer_services' => $customerServices,
        ]);
    }
    
}
