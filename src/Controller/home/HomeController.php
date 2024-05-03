<?php

namespace App\Controller\home;

use App\Entity\Admin;
use App\Entity\Professeur;
use App\Entity\Student;
use App\Form\StudentType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(Request $request, ManagerRegistry $doctrine )
    {
        return $this->render('index.html.twig',[
            
        ]);
    }

    #[Route('/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Set role as "student"
            $student->setRole("student");
    
            // Check if email already exists
            $existingEmail = $entityManager->getRepository(Student::class)->findOneBy(['email' => $student->getEmail()]);
            if ($existingEmail) {
                $form->get('email')->addError(new FormError('This email is already in use.'));
                return $this->render('student/new.html.twig', [
                    'student' => $student,
                    'form' => $form->createView(),
                ]);
            }
    
            // Check if CIN already exists
            $existingCIN = $entityManager->getRepository(Student::class)->findOneBy(['cin' => $student->getCin()]);
            if ($existingCIN) {
                $form->get('cin')->addError(new FormError('This CIN is already in use.'));
                return $this->render('student/new.html.twig', [
                    'student' => $student,
                    'form' => $form->createView(),
                ]);
            }
    
            $entityManager->persist($student);
            $entityManager->flush();
    
            return $this->redirectToRoute('login');
        }
    
        return $this->render('student/new.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
        ]);
    }
    
   
    #[Route('/register', name: 'register')]
    public function register(Request $request, ManagerRegistry $doctrine )
    {
        return $this->render('registration/register.html.twig',[
            
        ]);
    }
    #[Route('/submitRegister', name: 'submitRegister')]
    public function submitRegistration(Request $request, ManagerRegistry $doctrine): Response
    {
        // Create a new instance of the Student entity
        $student = new Student();
    
        // Get form data from the request
        $formData = $request->request->all();
    
        // Populate the student object with form data
        $student->setFirstName($formData['firstName']);
        $student->setLastName($formData['lastName']);
        $student->setEmail($formData['email']);
        $student->setPhoneNumber($formData['phoneNumber']);
        $student->setCin($formData['cin']);
        $birthDate = new DateTime($formData['birthDate']);
        $student->setBirthDate($birthDate);
        $student->setPassword($formData['password']);
        $student->setRole("student");

        // Get the entity manager
        $entityManager = $doctrine->getManager();
    
        // Persist the student object to the database
        $entityManager->persist($student);
        $entityManager->flush();
    
        // Redirect to a success page or display a success message
        return $this->redirectToRoute('login');
    }

    #[Route('/login', name: 'login')]
    public function redirectToLogin(){
        return $this->render('login/login.html.twig',[
            'recaptcha3_secret_key' => $this->getParameter('recaptcha3_private_key')
            
        ]);
    }
    #[Route('/professeurs', name: 'professeurs')]
public function professeurs(SessionInterface $session, ManagerRegistry $doctrine): Response
{
    // Retrieve the stored user ID from the session
    $userId = $session->get('user')['id'];

    // Retrieve the user object from the database based on the user ID
    $user = $doctrine->getRepository(Admin::class)->find($userId);

    // Fetch all students from the database
$professeurs = $doctrine->getRepository(Professeur::class)->findAll();

    // Check if the user exists
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Pass the user object to the template
    return $this->render('BackOffice/professeurs.html.twig', ['user' => $user,'professeurs' => $professeurs]);
}
    

}
