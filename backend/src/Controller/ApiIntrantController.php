<?php

namespace App\Controller;

use App\Repository\IntrantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiIntrantController extends AbstractController
{
    #[Route('/intrants', name: 'get_intrants', methods: ['GET'])]
    public function getIntrants(IntrantRepository $intrantRepository): JsonResponse
    {
        $intrants = $intrantRepository->findAll();

        $data = [];
        foreach ($intrants as $intrant) {
            $data[] = [
                'id_intrant' => $intrant->getIdIntrant(),
                'nomIntrant' => $intrant->getNomIntrant()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
