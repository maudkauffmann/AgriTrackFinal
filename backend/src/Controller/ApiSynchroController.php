<?php

namespace App\Controller;

use App\Entity\Realiser;
use App\Entity\Tache;
use App\Entity\Ouvrier;
use App\Entity\Campagne;
use App\Entity\Intrant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiSynchroController extends AbstractController
{
    #[Route('/api/synchro/tache', name: 'api_synchro_tache', methods: ['POST', 'OPTIONS'])]
    public function synchroTaches(Request $request, EntityManagerInterface $em): JsonResponse
    {
        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse(null, 200);
        }

        $payload = json_decode($request->getContent(), true);

        if (!$payload || !isset($payload['actions'])) {
            return new JsonResponse(['error' => 'Données manquantes'], 400);
        }

        $syncedUuids = [];

        foreach ($payload['actions'] as $action) {
            $uuid = $action['uuid'] ?? null;
            if (!$uuid) continue;

            // Vérification si déjà synchronisé
            $dejaSynchro = $em->getRepository(Realiser::class)->findOneBy(['uuidLocal' => $uuid]);
            if ($dejaSynchro) {
                $syncedUuids[] = $uuid;
                continue;
            }

            // Récupération des objets entités depuis la base
            $tache = $em->getRepository(Tache::class)->find($action['tache_id'] ?? null);
            $ouvrier = $em->getRepository(Ouvrier::class)->find($action['ouvrier_id'] ?? null);
            $campagne = $em->getRepository(Campagne::class)->find($action['campagne_id'] ?? null);

            // On ne crée la réalisation que si les entités liées existent
            if (!$tache || !$ouvrier || !$campagne) {
                continue;
            }

            $realisation = new Realiser();
            $realisation->setTache($tache);
            $realisation->setOuvrier($ouvrier);
            $realisation->setCampagne($campagne);

            // Gestion de l'intrant (optionnel)
            $intrantId = $action['intrant_id'] ?? null;
            if ($intrantId) {
                $intrant = $em->getRepository(Intrant::class)->find($intrantId);
                $realisation->setIntrant($intrant);
            }

            // Gestion des champs simples
            $realisation->setDateRealisation(!empty($action['date_realisation']) ? new \DateTime($action['date_realisation']) : new \DateTime());
            $realisation->setQuantiteIntrant(isset($action['quantite_intrant']) ? (float)$action['quantite_intrant'] : null);
            $realisation->setUuidLocal($uuid);

            $em->persist($realisation);
            $syncedUuids[] = $uuid;
        }

        try {
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur de base de données : ' . $e->getMessage()], 500);
        }

        return new JsonResponse([
            'status' => 'success',
            'synced_uuids' => $syncedUuids
        ], 200);
    }
}
