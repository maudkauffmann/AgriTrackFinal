<?php

namespace App\Controller;

use App\Entity\Ouvrier;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ApiOuvrierController extends AbstractController
{
    #[Route('/api/ouvriers', name: 'api_ouvrier_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $ouvriers = $em->getRepository(Ouvrier::class)->findAll();
        $data = [];

        foreach ($ouvriers as $ouvrier) {
            $id = $ouvrier->getIdOuvrier();
            $idString = (is_object($id) && method_exists($id, 'toBase58')) ? $id->toBase58() : (string)$id;

            $data[] = [
                'id_ouvrier' => $idString,
                'nomOuvrier' => $ouvrier->getNomOuvrier() ?? 'Ouvrier sans nom'
            ];
        }

        return new JsonResponse(array_values($data), 200);
    }

    #[Route('/api/ouvriers/create', name: 'api_ouvrier_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!($user instanceof Utilisateur) || !$user->getIdRole()->isCreationOuvrier()) {
            return new JsonResponse(['error' => 'Permission refusée'], 403);
        }

        $data = json_decode($request->getContent(), true);

        $ouvrier = new Ouvrier();
        $ouvrier->setNomOuvrier($data['nomOuvrier']);
        $ouvrier->setTelOuvrier($data['telOuvrier']);
        $ouvrier->setIdUtilisateur($user); // Liaison automatique

        $em->persist($ouvrier);
        $em->flush();

        return new JsonResponse(['id_ouvrier' => (string)$ouvrier->getIdOuvrier()], 201);
    }
}
