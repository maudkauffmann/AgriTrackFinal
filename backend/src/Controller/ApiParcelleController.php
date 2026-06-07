<?php

namespace App\Controller;

use App\Repository\ParcelleRepository;
use App\Repository\RealiserRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\OuvrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Realiser; // <-- ON UTILISE L'ENTITÉ REALISER ICI

class ApiParcelleController extends AbstractController
{
    /**
     * Récupère la liste des parcelles pour une plantation donnée
     */
    #[Route('/api/plantations/{plantationId}/parcelles', name: 'api_plantation_parcelles', methods: ['GET'])]
    public function getParcellesByPlantation(int $plantationId, ParcelleRepository $parcelleRepository): JsonResponse
    {
        $parcelles = $parcelleRepository->findBy(['id_plantation' => $plantationId]);

        return $this->json($parcelles, 200, [], ['groups' => 'parcelle:read']);
    }

    #[Route('/api/parcelles/{id}/actions', name: 'api_parcelle_actions', methods: ['GET'])]
    public function getActionsParcelle(string $id, EntityManagerInterface $em): JsonResponse
    {
        $conn = $em->getConnection();

        // Ajout du LEFT JOIN vers intrant pour récupérer nom et quantité
        $sql = '
        SELECT r.*, t.nomTache, o.nomOuvrier, i.nomIntrant
        FROM realiser r
        INNER JOIN campagne c ON r.id_campagne = c.id_campagne
        INNER JOIN tache t ON r.id_tache = t.id_tache
        INNER JOIN ouvrier o ON r.id_ouvrier = o.id_ouvrier
        LEFT JOIN intrant i ON r.id_intrant = i.id_intrant
        WHERE c.id_parcelle = :parcelleId
        ORDER BY r.dateRealisation DESC
    ';

        $resultSet = $conn->executeQuery($sql, ['parcelleId' => $id]);
        $actions = $resultSet->fetchAllAssociative();

        return new JsonResponse($actions, 200);
    }

    /**
     * Assigne manuellement un ouvrier et une action à une parcelle
     */
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

        return new JsonResponse(['success' => 'Assignation réussie !'], 200);
    }

    /**
     * Synchronise et enregistre les actions envoyées par le formulaire React
     * Gère le format d'envoi structuré { actions: [...] } pour s'adapter à Dexie
     * @throws ORMException
     */
    #[Route('/api/synchro/tache', name: 'api_synchro_tache', methods: ['POST', 'OPTIONS'])]
    public function synchroniserDonnees(Request $request, EntityManagerInterface $em): JsonResponse
    {
        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse(null, 200);
        }

        $payload = json_decode($request->getContent(), true);

        // Détection du wrapper "actions" envoyé par React
        $actionsALire = isset($payload['actions']) ? $payload['actions'] : [$payload];

        $syncedUuids = [];

        foreach ($actionsALire as $actionData) {
            if (empty($actionData) || !isset($actionData['uuid'])) {
                continue;
            }

            $uuid = $actionData['uuid'];

            // Anti-doublon : Vérifie si cet UUID local n'est pas déjà en base de données
            $dejaSynchro = $em->getRepository(Realiser::class)->findOneBy(['uuidLocal' => $uuid]);
            if ($dejaSynchro) {
                $syncedUuids[] = $uuid;
                continue;
            }

            $tacheId = $actionData['tache_id'] ?? null;
            $ouvrierId = $actionData['ouvrier_id'] ?? null;
            $campagneId = $actionData['campagne_id'] ?? '1';
            $intrantId = $actionData['intrant_id'] ?? '0'; // Force "0" pour éviter l'erreur NOT NULL

            if (!$tacheId || !$ouvrierId) {
                continue;
            }

            // Hydratation sécurisée avec des références
            $realisation = new Realiser();
            $realisation->setTache($em->getReference(\App\Entity\Tache::class, $tacheId));
            $realisation->setOuvrier($em->getReference(\App\Entity\Ouvrier::class, $ouvrierId));
            $realisation->setCampagne($em->getReference(\App\Entity\Campagne::class, $campagneId));

            if ($intrantId != '0') {
                $realisation->setIntrant($em->getReference(\App\Entity\Intrant::class, $intrantId));
            }

            $realisation->setDateRealisation(new \DateTime());
            $realisation->setUuidLocal($uuid);

            $em->persist($realisation);
            $syncedUuids[] = $uuid;
        }

        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'synced_uuids' => $syncedUuids
        ], 200);
    }
}
