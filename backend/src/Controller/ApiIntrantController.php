<?php

namespace App\Controller;

use App\Repository\IntrantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiIntrantController extends AbstractController
{
    #[Route('/api/intrants', name: 'api_intrants_list', methods: ['GET'])]
    public function getIntrants(IntrantRepository $repo): JsonResponse
    {
        $intrants = $repo->findAll();
        $data = [];
        foreach ($intrants as $i) {
            $data[] = [
                'id_intrant' => $i->getId(),
                'nomIntrant' => $i->getNomIntrant(),
                'stock' => (int)$i->getStock(),
                'unite' => $i->getUnite()?->getNomUnite(),
                'coutUnitaire' => $i->getCoutUnitaire()
            ];
        }
        return new JsonResponse($data, 200);
    }

    #[Route('/api/intrants/{id}/decrémenter', name: 'api_intrants_decr', methods: ['POST'])]
    public function decrementarStock(int $id, Request $request, IntrantRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $quantite = $data['quantite'] ?? 0;

        $intrant = $repo->find($id);
        if (!$intrant || (float)$intrant->getStock() < $quantite) {
            return new JsonResponse(['error' => 'Stock insuffisant'], 422);
        }

        $intrant->setStock((string)((float)$intrant->getStock() - $quantite));
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
}
