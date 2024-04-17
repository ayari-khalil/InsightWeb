<?php

namespace App\Controller;
use App\Entity\Quiz;
use App\Form\TQuizType;
use App\Repository\QuizRepository;
use App\Entity\Tests;
use App\Form\TestsType;
use App\Repository\TestsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/examhome', name: 'app_Exam_Home', methods: ['GET', 'POST'])]
    public function index(Request $request, QuizRepository $QuizRepository, TestsRepository $testsRepository): Response
    {
        $searchQuery = $request->query->get('search');
    
        if ($searchQuery) {
            $tests = $testsRepository->findByMatiere($searchQuery);
        } else {
            $tests = $testsRepository->findAll();
        }
        $quizzes = $QuizRepository->findAll();
        return $this->render('ExamHome.html.twig', [ 
            'tests' => $tests,
            'searchQuery' => $searchQuery,
            'quizzes' => $quizzes,
        ]);
    }
    #[Route('/home', name: 'app_Home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }
}
