<?php

namespace App\Controller;

use App\Repository\ParcelleRepository;
use App\Repository\RealiserRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tache;

class ApiParcelleController extends AbstractController
{
    #[Route('/api/plantations/{plantationId}/parcelles', name: 'api_plantation_parcelles', methods: ['GET'])]
    public function getParcellesByPlantation(int $plantationId, ParcelleRepository $parcelleRepository): JsonResponse
    {
        $parcelles = $parcelleRepository->findBy(['id_plantation' => $plantationId]);

        return $this->json($parcelles, 200, [], ['groups' => 'parcelle:read']);
    }

    #[Route('/api/parcelles/{id}/assigner-action', name: 'api_parcelle_assigner_action', methods: ['POST'])]
    public function assignerAction(
        int $id,
        Request $request,
        ParcelleRepository $parcelleRepository,
        UtilisateurRepository $utilisateurRepository,
        RealiserRepository $actionRepository,
        EntityManagerInterface $em
    ): JsonResponse {

        $parcelle = $parcelleRepository->find($id);
        if (!$parcelle) {
            return new JsonResponse(['error' => 'Parcelle introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $ouvrierId = $data['ouvrierId'] ?? null;
        $actionId = $data['actionId'] ?? null;

        if (!$ouvrierId || !$actionId) {
            return new JsonResponse(['error' => 'Données reçues incomplètes'], 400);
        }

        $ouvrier = $utilisateurRepository->find($ouvrierId);
        $action = $actionRepository->find($actionId);

        if (!$ouvrier || !$action) {
            return new JsonResponse(['error' => 'Ouvrier ou Action introuvable en BDD'], 404);
        }
        $parcelle->setOuvrier($ouvrier);
        $parcelle->setAction($action);
        $em->flush();

        return new JsonResponse(['success' => 'Assignation réussie ! Visible dans EasyAdmin.'], 200);
    }

    #[Route('/api/synchro/tache', name: 'api_synchro_tache', methods: ['POST'])]
    public function synchroniserDonnees(
        Request $request,
        EntityManagerInterface $entityManager,
        ParcelleRepository $parcelleRepository,
        UtilisateurRepository $utilisateurRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Validation des données minimales requises
        if (!$data || !isset($data['nom']) || !isset($data['uuid'])) {
            return new JsonResponse(['error' => 'Données incomplètes (nom et uuid requis)'], 400);
        }

        // Pour éviter les doublons de synchronisation avec l'UUID local
        $tacheExistante = $entityManager->getRepository(Tache::class)->findOneBy([
            'uuidLocal' => $data['uuid']
        ]);

        if ($tacheExistante) {
            return new JsonResponse([
                'status' => 'Déjà synchronisé',
                'id' => $tacheExistante->getId()
            ], 200);
        }

        // Création de la tâche de base
        $tache = new Tache();
        $tache->setNomTache($data['nom']);
        $tache->setUuidLocal($data['uuid']);

        // Liaison de la Parcelle si l'ID est fourni par le Front
        if (!empty($data['parcelle_id'])) {
            $parcelle = $parcelleRepository->find($data['parcelle_id']);
            if ($parcelle) {
                $tache->setParcelle($parcelle);
            }
        }

        // Liaison de l'Ouvrier si l'ID est fourni par le Front
        if (!empty($data['ouvrier_id'])) {
            $ouvrier = $utilisateurRepository->find($data['ouvrier_id']);
            if ($ouvrier) {
                $tache->setOuvrier($ouvrier);
            }
        }

        // Sauvegarde finale
        $entityManager->persist($tache);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Tâche synchronisée avec succès',
            'id' => $tache->getId()
        ], 201);
    }
}
