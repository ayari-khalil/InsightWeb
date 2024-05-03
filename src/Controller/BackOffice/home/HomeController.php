<?php

namespace App\Controller\BackOffice\home;

use App\Entity\Admin;
use App\Entity\BannedUsers;
use App\Entity\Professeur;
use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{
    #[Route('/etudiants', name: 'etudiants')]
    public function etudiants(SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        // Retrieve the stored user ID from the session
        $userId = $session->get('user')['id'];
    
        // Retrieve the user object from the database based on the user ID
        $user = $doctrine->getRepository(Admin::class)->find($userId);
    
        // Fetch all students from the database
        $students = $doctrine->getRepository(Student::class)->findAll();
    
        // Fetch ban status for each student
        $studentBanStatus = [];
        foreach ($students as $student) {
            $bannedUser = $doctrine->getRepository(BannedUsers::class)->findOneBy(['studentId' => $student->getId()]);
            $studentBanStatus[$student->getId()] = $bannedUser ? true : false;
        }
    
        // Pass the user object and student ban status to the template
        return $this->render('BackOffice/etudiants.html.twig', [
            'user' => $user,
            'students' => $students,
            'studentBanStatus' => $studentBanStatus,
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
    #[Route('/ban/student/{id}', name: 'ban_student')]
    public function banStudent($id, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Find the student entity by ID
        $student = $entityManager->getRepository(Student::class)->find($id);
        
        // Check if student exists
        if (!$student) {
            throw $this->createNotFoundException('Student not found');
        }
        
        // Create a new instance of the BannedUser entity
        $bannedUser = new BannedUsers();
        $bannedUser->setStudentId($student->getId());
        
        // Persist the banned user to the database
        $entityManager->persist($bannedUser);
        $entityManager->flush();
        
        // Redirect back to the page where the student list is displayed
        return $this->redirectToRoute('etudiants');
    }

    #[Route('/unban/student/{id}', name: 'unban_student')]
public function unbanStudent($id, EntityManagerInterface $entityManager): RedirectResponse
{
    // Find the banned user entry by student ID
    $bannedUser = $entityManager->getRepository(BannedUsers::class)->findOneBy(['studentId' => $id]);
    
    // Check if banned user exists
    if (!$bannedUser) {
        throw $this->createNotFoundException('Banned user not found');
    }
    
    // Remove the banned user entry from the database
    $entityManager->remove($bannedUser);
    $entityManager->flush();
    
    // Redirect back to the page where the student list is displayed
    return $this->redirectToRoute('etudiants');
}
#[Route('/students', name: 'student_list')]
public function studentList(Request $request, StudentRepository $studentRepository): Response
{
    // Get the filter value from the request
    $filter = $request->query->get('filter');

    // Fetch students sorted alphabetically by first name
    $students = $studentRepository->findAllSortedByFirstName($filter);

    return $this->render('BackOffice/etudiants.html.twig', [
        'students' => $students,
    ]);
}

#[Route('/students/sorted-by-first-name', name: 'student_list_sorted_by_first_name')]
public function studentListSortedByFirstName(StudentRepository $studentRepository): JsonResponse
{
    // Fetch students sorted alphabetically by first name
    $students = $studentRepository->findAllSortedByFirstName();

    return $this->json($students);
}
#[Route('/students/search', name: 'student_search')]
public function searchStudents(Request $request, StudentRepository $studentRepository): Response
{
    $searchTerm = $request->query->get('q');

    // Perform the search in the repository
    $students = $studentRepository->search($searchTerm);

    // Prepare an array of student data
    $studentData = [];
    foreach ($students as $student) {
        $studentData[] = [
            'id' => $student->getId(),
            'firstName' => $student->getFirstName(),
            'lastName' => $student->getLastName(),
            'email' => $student->getEmail(),
            'birthDate' => $student->getBirthDate()->format('Y-m-d'), // Format the date as needed
            'phoneNumber' => $student->getPhoneNumber(),
            'cin' => $student->getCin(),
            // Add more fields if needed
        ];
    }

    // Return the student data as JSON response
    return $this->json($studentData);
}


    
}