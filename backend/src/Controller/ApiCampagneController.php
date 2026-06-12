<?php

namespace App\Controller;

use App\Repository\CampagneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiCampagneController extends AbstractController
{
    #[Route('/campagnes', name: 'get_campagnes', methods: ['GET'])]
    public function getCampagnes(CampagneRepository $campagneRepository): JsonResponse
    {
        $campagnes = $campagneRepository->findBy(['dateFin' => null]);

        $data = [];
        foreach ($campagnes as $campagne) {
            $data[] = [
                'id_campagne' => $campagne->getIdCampagne(),
                'nomCampagne' => $campagne->getNomCampagne(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
