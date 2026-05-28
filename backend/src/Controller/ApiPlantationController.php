<?php

namespace App\Controller;

use App\Repository\PlantationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiPlantationController extends AbstractController
{
    #[Route('/api/plantations', name: 'api_plantations', methods: ['GET'])]
    public function index(PlantationRepository $repo): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Accès refusé. Non authentifié.'], 401);
        }

        $plantations = $repo->findBy(['id_utilisateur' => $user]);

        return $this->json($plantations, 200, [], ['groups' => 'plantation:read']);
    }
}
