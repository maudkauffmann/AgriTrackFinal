<?php

namespace App\Controller;

use App\Entity\Tache;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiTacheController extends AbstractController
{
    #[Route('/api/taches', name: 'api_taches_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $taches = $em->getRepository(Tache::class)->findAll();
        $data = [];

        foreach ($taches as $tache) {
            $id = $tache->getIdTache();
            $idString = (is_object($id) && method_exists($id, 'toBase58')) ? $id->toBase58() : (string)$id;

            $data[] = [
                'id_tache' => $idString,
                'nomTache' => $tache->getNomTache() ?? 'Tâche sans nom'
            ];
        }

        return new JsonResponse(array_values($data), 200);
    }
}
