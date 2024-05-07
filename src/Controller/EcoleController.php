<?php

namespace App\Controller;

use App\Entity\Ecole;
use App\Form\EcoleType;
use App\Repository\EcoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/ecole')]
class EcoleController extends AbstractController
{
    #[Route('/', name: 'app_ecole_index', methods: ['GET'])]
    public function index(Request $request, EcoleRepository $ecoleRepository): Response
    {
        $sortBy = $request->query->get('sortBy', 'nom'); // Par défaut, trier par nom
    
        // Vérifiez la valeur sélectionnée dans le menu déroulant et définissez la propriété de tri en conséquence
        switch ($sortBy) {
            case 'nom':
                $sortByProperty = 'nom';
                break;
            case 'id':
                $sortByProperty = 'id';
                break;
            case 'nb_professeur':
                $sortByProperty = 'nb_professeur';
                break;
            default:
                $sortByProperty = 'nom'; // Par défaut, trier par nom
        }
    
        $ecoles = $ecoleRepository->findAllSortedBy($sortByProperty);
    
        return $this->render('ecole/index.html.twig', [
            'ecoles' => $ecoles,
        ]);
    }
    


    #[Route('/ecolehome', name: 'app_Ecole_Home', methods: ['GET', 'POST'])]
    public function recherche(Request $request, EcoleRepository $ecoleRepository): Response
    {
        $searchQuery = $request->query->get('search');

        if ($searchQuery) {
            $ecoles = $ecoleRepository->findByNom($searchQuery);
        } else {
            $ecoles = $ecoleRepository->findAll();
        }
        // Assuming $QuizRepository and $quizzes are defined somewhere else.
        return $this->render('ecole/index.html.twig', [
            'ecoles' => $ecoles,
            'searchQuery' => $searchQuery,
           
        ]);
    }


    #[Route('/new', name: 'app_ecole_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ecole = new Ecole();
        $form = $this->createForm(EcoleType::class, $ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ecole);
            $entityManager->flush();

            return $this->redirectToRoute('app_ecole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ecole/new.html.twig', [
            'ecole' => $ecole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ecole_show', methods: ['GET'])]
    public function show(Ecole $ecole): Response
    {
        return $this->render('ecole/show.html.twig', [
            'ecole' => $ecole,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ecole_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ecole $ecole, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EcoleType::class, $ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ecole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ecole/edit.html.twig', [
            'ecole' => $ecole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ecole_delete', methods: ['POST'])]
    public function delete(Request $request, Ecole $ecole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ecole->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ecole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ecole_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/listEcole', name: 'app_ecole_list', methods: ['GET'])]
public function showEcoleList(EcoleRepository $ecoleRepository): Response
{
    $ecoles = $ecoleRepository->findAll();
    
    return $this->render('ecole/EcoleList.twig', [
        'ecoles' => $ecoles,
    ]);
}
#[Route('/map', name: 'map')]
public function map(): Response
{
    return $this->render('ecole/maps.html.twig');
}


#[Route('/getCoords/{id}', name: 'get_coords', methods: ['GET'])]
public function getCoordinates(int $id, EcoleRepository $ecoleRepository): JsonResponse
{
    $ecole = $ecoleRepository->find($id);

    if (!$ecole) {
        return new JsonResponse(['error' => 'Ecole not found'], Response::HTTP_NOT_FOUND);
    }

    // Logic to retrieve coordinates from the school's address
    $coordinates = $this->geocodeAddress($ecole->getAdresse());

    return new JsonResponse($coordinates);
}

public function geocodeAddress(string $address): array
{
    $apiKey = 'AIzaSyDCqTa7_RXz8n5WlqEzSE1qjcGxoaI77N4';
    $client = new Client();
    try {
        $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json', [
            'query' => [
                'address' => $address,
                'key' => $apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Vérifie si la réponse est réussie et contient des résultats
        if ($response->getStatusCode() === 200 && isset($data['results'][0]['geometry']['location'])) {
            $location = $data['results'][0]['geometry']['location'];
            return [
                'latitude' => $location['lat'],
                'longitude' => $location['lng'],
            ];
        } else {
            
            return [];
        }
    } catch (\Exception $e) {
        // En cas d'erreur lors de la requête HTTP
        return [];
    }
}



#[Route('/test-geocode', name: 'test_geocode')]
public function testGeocode(Request $request): Response
{
    $address = $request->query->get('address'); // Récupérer l'adresse depuis la requête
    
    // Appeler la méthode geocodeAddress pour obtenir les coordonnées
    $coordinates = $this->geocodeAddress($address);
    
    // Retourner la réponse sous forme de vue Twig avec les coordonnées
    return $this->render('ecole/test_geocode.html.twig', [
        'coordinates' => $coordinates,
    ]);
}

}
