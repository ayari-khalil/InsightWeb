<?php
namespace App\Controller\login;

use App\Entity\Admin;
use App\Entity\BannedUsers;
use App\Entity\Professeur;
use App\Entity\Student;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Form\UpdatePasswordType;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginController extends AbstractController{
    #[Route('/signin', name: 'signin')]
    public function login(Request $request, ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
    
        // Check if the email exists in the Student table
        $student = $doctrine->getRepository(Student::class)->findOneBy(['email' => $email]);
    
        // Check if student is banned
        if ($student) {
            $bannedUser = $doctrine->getRepository(BannedUsers::class)->findOneBy(['studentId' => $student->getId()]);
            if ($bannedUser) {
                // If the student is banned, return an error message
                $errorMessage = 'You are banned. Please contact the administrator for further assistance.';
                return $this->render('login/login.html.twig', ['error' => $errorMessage]);
            }
        }
    
        // Attempt to log in
        if ($student && $password === $student->getPassword()) {
            // Store student information in the session
            $session->set('user', [
                'id' => $student->getId(),
                'email' => $student->getEmail(),
                'role' => 'student',
                // Add other relevant student data here
            ]);
    
            // Redirect to the student profile page
            return $this->redirectToRoute('studentprofile', ['user' => $student->getId()]);
        }
    
        // Check if the email exists in the Admin table
        $admin = $doctrine->getRepository(Admin::class)->findOneBy(['email' => $email]);
        if ($admin && $password === $admin->getPassword()) {
            // Store admin information in the session
            $session->set('user', [
                'id' => $admin->getId(),
                'email' => $admin->getEmail(),
                'role' => 'admin',
                // Add other relevant admin data here
            ]);
    
            // Redirect to the admin profile page
            return $this->redirectToRoute('adminprofile', ['user' => $admin->getId()]);
        }
    
        // If the email does not exist in either table or the password is incorrect, return an error message
        $errorMessage = 'Invalid email or password';
        return $this->render('login/login.html.twig', ['error' => $errorMessage]);
    }
    
    


#[Route('/studentprofile', name: 'studentprofile')]
public function studentProfile(SessionInterface $session, ManagerRegistry $doctrine): Response
{
    // Retrieve the stored user ID from the session
    $userId = $session->get('user')['id'];

    // Retrieve the user object from the database based on the user ID
    $user = $doctrine->getRepository(Student::class)->find($userId);

    // Check if the user exists
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Pass the user object to the template
    return $this->render('student/studentDashboard.html.twig', ['user' => $user]);
}
#[Route('/adminprofile', name: 'adminprofile')]
public function adminProfile(SessionInterface $session, ManagerRegistry $doctrine): Response
{
    // Retrieve the stored user ID from the session
    $userId = $session->get('user')['id'];

    // Retrieve the user object from the database based on the user ID
    $user = $doctrine->getRepository(Admin::class)->find($userId);

    // Check if the user exists
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Pass the user object to the template
    return $this->render('BackOffice/adminDashboard.html.twig', ['user' => $user]);
}


#[Route('/moncompte', name: 'mon_compte')]
public function monCompte(SessionInterface $session, ManagerRegistry $doctrine): Response
{
    // Retrieve the user object from the session
    $userId = $session->get('user')['id'];

    $user = $doctrine->getRepository(Student::class)->find($userId);

    // Check if the user exists
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Render the monCompteStudent.html.twig template and pass the user object to it
    return $this->render('student/monCompteStudent.html.twig', ['user' => $user]);
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
    $form = $this->createForm(UpdatePasswordType::class, null, ['user' => $user]);

    // Handle form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Process form submission and update the password
        $data = $form->getData();
        $currentPassword = $data['currentPassword'];
        $newPassword = $data['newPassword'];

        // Check if the current password matches the stored password
        if ($currentPassword !== $user->getPassword()) {
            // Add a form error for incorrect current password
            $form->get('currentPassword')->addError(new FormError('Incorrect current password'));
        } else {
            // Update the user's password with the new password
            $user->setPassword($newPassword);

            // Persist the changes to the database
            $entityManager->flush();

            // Add a flash message for successful password update
            $this->addFlash('success', 'Password updated successfully');

            // Redirect to another page after updating the password
            return $this->redirectToRoute('mon_compte');
        }
    }

    // Render the template with the form
    return $this->render('student/changePassword.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
}
#[Route('/changermdpAdmin/{id}', name: 'changermdpAdmin')]
public function changermdpAdmin(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    // Retrieve the user object from the database based on the provided ID
    $user = $entityManager->getRepository(Admin::class)->find($id);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Create the form
    $form = $this->createForm(UpdatePasswordType::class, null, ['user' => $user]);

    // Handle form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Process form submission and update the password
        $data = $form->getData();
        $currentPassword = $data['currentPassword'];
        $newPassword = $data['newPassword'];

        // Check if the current password matches the stored password
        if ($currentPassword !== $user->getPassword()) {
            // Add a form error for incorrect current password
            $form->get('currentPassword')->addError(new FormError('Incorrect current password'));
        } else {
            // Update the user's password with the new password
            $user->setPassword($newPassword);

            // Persist the changes to the database
            $entityManager->flush();

            // Add a flash message for successful password update
            $this->addFlash('success', 'Password updated successfully');

            // Redirect to another page after updating the password
            return $this->redirectToRoute('mon_compte_admin');
        }
    }

    // Render the template with the form
    return $this->render('admin/changePassword.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
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
#[Route('/moncompteAdmin', name: 'mon_compte_admin')]
public function monCompteAdmin(SessionInterface $session, ManagerRegistry $doctrine): Response
{
    // Retrieve the user object from the session
    $userId = $session->get('user')['id'];

    $user = $doctrine->getRepository(Admin::class)->find($userId);

    // Check if the user exists
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Render the monCompteStudent.html.twig template and pass the user object to it
    return $this->render('BackOffice/monCompteAdmin.html .twig', ['user' => $user]);
}

#[Route('/forgot-password', name: 'forgot_password')]
public function forgotPassword(
    Request $request,
    MailerInterface $mailer,
    SessionInterface $session
): Response
{
    $error = '';

    if ($request->isMethod('POST')) {
        $email = $request->request->get('email');

        // Valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format d\'email invalide';
        } else {
            // Générer un code aléatoire
            $code = mt_rand(100000, 999999);

            // Envoyer le code par email
            $message = (new Email())
                ->from('pinsight76@gmail.com')
                ->to($email)
                ->subject('Code de réinitialisation du mot de passe')
                ->html("<p>Votre code de réinitialisation du mot de passe est : $code</p>");

            $mailer->send($message);

            // Enregistrez le code de vérification et l'email dans la session
            $session->set('reset_password_email', $email);
            $session->set('reset_password_code', $code);

            // Redirigez l'utilisateur vers la page de réinitialisation du mot de passe
            return $this->redirectToRoute('reset_password');
        }
    }

    return $this->render('login/forgot_password_index.html.twig', [
        'error' => $error,
    ]);
}


#[Route('/reset-passwordPage', name: 'reset_password_page')]
public function resetPage(){
    return $this->render('login/resetPassword.html.twig',[
        
    ]);
}



#[Route('/reset-password', name: 'reset_password')]
public function resetPassword(
    Request $request,
    ManagerRegistry $doctrine,
    SessionInterface $session
): Response
{
    $error = '';

    // Récupérez l'email et le code de la session
    $email = $session->get('reset_password_email');
    $code = $session->get('reset_password_code');

    if ($request->isMethod('POST')) {
        $enteredCode = $request->request->get('code');
        $password = $request->request->get('password');

        // Vérifiez si le code entré correspond à celui enregistré dans la session
        if ($enteredCode != $code) {
            $error = 'Code de vérification invalide';
        } else {
            // Récupérez l'utilisateur à partir de l'email enregistré dans la session
            $student = $doctrine->getRepository(Student::class)->findOneBy(['email' => $email]);

            if (!$student) {
                $error = 'Utilisateur non trouvé';
            } else {
                // Mettez à jour le mot de passe de l'utilisateur
                $student->setPassword($password);
                $entityManager = $doctrine->getManager();
                $entityManager->flush();

                // Redirigez l'utilisateur vers la page de connexion
                return $this->redirectToRoute('login');
            }
        }
    }

    return $this->render('login/resetPassword.html.twig', [
        'error' => $error,
    ]);
}


}