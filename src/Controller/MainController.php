<?php
namespace App\Controller;

use App\Repository\TestsRepository;
use App\Repository\QuestionsRepository; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MainController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TestsRepository $testsRepository;
    private QuestionsRepository $questionRepository; // Define QuestionsRepository

    public function __construct(EntityManagerInterface $entityManager, TestsRepository $testsRepository, QuestionsRepository $questionRepository)
    {
        $this->entityManager = $entityManager;
        $this->testsRepository = $testsRepository;
        $this->questionRepository = $questionRepository; // Initialize QuestionsRepository
    }
    #[Route('/examhome', name: 'app_Exam_Home', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $searchQuery = $request->query->get('search');

        if ($searchQuery) {
            $tests = $this->testsRepository->findByMatiere($searchQuery);
        } else {
            $tests = $this->testsRepository->findAll();
        }
        // Assuming $QuizRepository and $quizzes are defined somewhere else.
        return $this->render('ExamHome.html.twig', [
            'tests' => $tests,
            'searchQuery' => $searchQuery,
           
        ]);
    }

    #[Route('/home', name: 'app_Home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    
    
    #[Route('/pass-test', name: 'test_pass')]
    public function passTest(Request $request): Response
    {
        // Fetch all tests
        $tests = $this->testsRepository->findAll();

        // Fetch questions for each test
        $questionsByTest = [];
        foreach ($tests as $test) {
            $questionsByTest[$test->getTest_id()] = $this->questionRepository->findBy(['test' => $test]);
        }

        return $this->render('tests/pass.html.twig', [
            'tests' => $tests,
            'questionsByTest' => $questionsByTest,
        ]);
    }

    #[Route('/test/{id}/pass/confirmation', name: 'test_pass_confirmation')]
    public function confirmation(Tests $tests): Response
    {
        return $this->render('tests/pass_confirmation.html.twig', [
            'test' => $test,
        ]);
    }
    
}
