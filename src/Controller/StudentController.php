<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Student;
use App\Form\StudentType;
use App\Form\UpdatePasswordType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: 'app_student_index', methods: ['GET'])]
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $student = new Student();
    $form = $this->createForm(StudentType::class, $student);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Check if email already exists
        $existingStudent = $entityManager->getRepository(Student::class)->findOneBy(['email' => $student->getEmail()]);
        if ($existingStudent) {
            $form->get('email')->addError(new FormError('This email is already in use.'));
            return $this->render('student/new.html.twig', [
                'student' => $student,
                'form' => $form->createView(),
            ]);
        }

        $entityManager->persist($student);
        $entityManager->flush();

        return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('student/new.html.twig', [
        'student' => $student,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_student_show', methods: ['GET'])]
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_student_edit', methods: ['GET', 'POST'])]
    public function edit(ManagerRegistry $doctrine, Request $request, Student $student, EntityManagerInterface $entityManager,SessionInterface $session,): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        $userId = $session->get('user')['id'];

        // Retrieve the user object from the database based on the user ID
        $user = $doctrine->getRepository(Admin::class)->find($userId);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('etudiants', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student/edit.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_student_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $entityManager->remove($student);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('etudiants', [], Response::HTTP_SEE_OTHER);
    }
    
   
    #[Route('/{id}/editProfile', name: 'app_student_editProfile', methods: ['GET', 'POST'])]
    public function editProfile(ManagerRegistry $doctrine, Request $request, Student $student, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Create the form
        $form = $this->createForm(StudentType::class, $student, [
            'include_password_field' => false,
        ]);
    
        // Handle form submission
        $form->handleRequest($request);
    
        // Get the user ID from the session
        $userId = $session->get('user')['id'];
    
        // Retrieve the user object from the database based on the user ID
        $user = $doctrine->getRepository(Student::class)->find($userId);
    
        // Check if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist changes to the database
            $entityManager->flush();
    
            // Redirect to the 'mon_compte' route after successful submission
            return $this->redirectToRoute('mon_compte', [], Response::HTTP_SEE_OTHER);
        }
    
        // Render the form
        return $this->render('student/edit.html.twig', [
            'student' => $student,
            'form' => $form->createView(), // Pass form view instead of form object
            'user' => $user
        ]);
    }
    
    #[Route('/changermdp/{id}', name: 'changermdp')]
public function changermdp(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    // Retrieve the user object from the database based on the provided ID
    $user = $entityManager->getRepository(Student::class)->find($id);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Create the form
    $form = $this->createForm(UpdatePasswordType::class);
    dump($form);

    // Handle form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Process form submission and update the password
        $data = $form->getData();
        $currentPassword = $data['currentPassword'];
        $newPassword = $data['newPassword'];

        // Update the user's password with the new password
        $user->setPassword($newPassword);

        // Persist the changes to the database
        $entityManager->flush();

        // Redirect to another page after updating the password
        return $this->redirectToRoute('mon_compte');
    }

    // Render the template with the form
    return $this->render('student/changePassword.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
}

 
}
