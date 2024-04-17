<?php

namespace App\Controller;

use App\Entity\Tests;
use App\Form\TestsType;
use App\Repository\TestsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tests')]
class TestsController extends AbstractController
{
    #[Route('/', name: 'app_tests_index', methods: ['GET'])]
    public function index(TestsRepository $testsRepository): Response
    {
        return $this->render('tests/index.html.twig', [
            'tests' => $testsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tests_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $test = new Tests();
        $form = $this->createForm(TestsType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($test);
            $entityManager->flush();

            return $this->redirectToRoute('app_tests_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tests/new.html.twig', [
            'test' => $test,
            'form' => $form,
        ]);
    }

    #[Route('/{test_id}', name: 'app_tests_show', methods: ['GET'])]
    public function show(Tests $test): Response
    {
        return $this->render('tests/show.html.twig', [
            'test' => $test,
        ]);
    }

    #[Route('/{test_id}/edit', name: 'app_tests_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tests $test, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TestsType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tests_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tests/edit.html.twig', [
            'test' => $test,
            'form' => $form,
        ]);
    }

    #[Route('/{test_id}', name: 'app_tests_delete', methods: ['POST'])]
    public function delete(Request $request, Tests $test, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$test->getTest_id(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($test);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tests_index', [], Response::HTTP_SEE_OTHER);
    }
}
