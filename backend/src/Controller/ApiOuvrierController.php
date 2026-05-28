<?php

namespace App\Controller;

use App\Entity\Ouvrier;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiOuvrierController extends AbstractController
{
    #[Route('/ouvrier/add', name: 'ouvrier_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('CREATE_OUVRIER');
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Données invalides'], 400);
        }

        try {
            /** @var Utilisateur $user */
            $user = $this->getUser();

            $ouvrier = new Ouvrier();
            $ouvrier->setNomOuvrier($data['nom'] ?? '');
            $ouvrier->setTelOuvrier($data['tel'] ?? '');
            $ouvrier->setIdUtilisateur($user);

            $em->persist($ouvrier);
            $em->flush();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Ouvrier ajouté avec succès'
            ], 201);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Erreur lors de l\'ajout : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/ouvriers', name: 'ouvrier_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $ouvriers = $em->getRepository(Ouvrier::class)->findBy([
            'id_utilisateur' => $this->getUser()
        ]);

        return $this->json($ouvriers, 200, [], ['groups' => 'ouvrier:read']);
    }
}
