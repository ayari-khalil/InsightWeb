<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Form\UpdatePasswordType;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($admin);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Admin $admin, EntityManagerInterface $entityManager, SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);
        $userId = $session->get('user')['id'];
    
        // Retrieve the user object from the database based on the user ID
        $user = $doctrine->getRepository(Admin::class)->find($userId);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('mon_compte_admin', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(), // Pass the form view instead of the form itself
            'user' => $user
        ]);
    }
    

    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Admin $admin, EntityManagerInterface $entityManager): Response
    {
        // Check if CSRF token is valid
        if ($this->isCsrfTokenValid('delete' . $admin->getId(), $request->request->get('_token'))) {
            // Remove the admin entity
            $entityManager->remove($admin);
            $entityManager->flush();
        }
    
        // Redirect to the admin index page
        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/{id}/editProfile', name: 'app_admin_editProfile', methods: ['GET', 'POST'])]
    public function editProfile(ManagerRegistry $doctrine, Request $request, Admin $admin, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $form = $this->createForm(AdminType::class, $admin, [
            'include_password_field' => false,
            'include_role_field' => false,
        ]);
        $form->handleRequest($request);
        $userId = $session->get('user')['id'];
    
        // Retrieve the admin object from the database based on the user ID
        $user = $doctrine->getRepository(Admin::class)->find($userId);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('mon_compte', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('admin/editProfile.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(), // Pass the form view instead of the form itself
            'user' => $user
        ]);
    }
    
    #[Route('/changermdp/{id}', name: 'changermdpAdmin')]
    public function changermdp(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        // Retrieve the user object from the database based on the provided ID
        $user = $entityManager->getRepository(Admin::class)->find($id);
    
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
            return $this->redirectToRoute('mon_compteAdmin');
        }
    
        // Render the template with the form
        return $this->render('admin/changePassword.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
