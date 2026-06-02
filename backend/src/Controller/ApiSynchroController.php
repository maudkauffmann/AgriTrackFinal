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
            return new JsonResponse(['error' => 'Format JSON invalide ou clé "actions" manquante'], 400);
        }

        $syncedUuids = [];
        $batchSize = 5; // Nombre d'objets traités avant chaque flush
        $i = 0;

        foreach ($payload['actions'] as $action) {
            $uuid = $action['uuid'] ?? null;
            if (!$uuid) continue;

            // Vérification si déjà synchronisé
            $dejaSynchro = $em->getRepository(Realiser::class)->findOneBy(['uuidLocal' => $uuid]);
            if ($dejaSynchro) {
                $syncedUuids[] = $uuid;
                continue;
            }

            // Récupération des entités
            $tache = isset($action['tache_id']) ? $em->getRepository(Tache::class)->find($action['tache_id']) : null;
            $ouvrier = isset($action['ouvrier_id']) ? $em->getRepository(Ouvrier::class)->find($action['ouvrier_id']) : null;
            $campagne = isset($action['campagne_id']) ? $em->getRepository(Campagne::class)->find($action['campagne_id']) : null;

            // Si une relation obligatoire est manquante, on passe cette action
            if (!$tache || !$ouvrier || !$campagne) {
                continue;
            }

            $realisation = new Realiser();
            $realisation->setTache($tache);
            $realisation->setOuvrier($ouvrier);
            $realisation->setCampagne($campagne);

            if (!empty($action['intrant_id'])) {
                $intrant = $em->getRepository(Intrant::class)->find($action['intrant_id']);
                $realisation->setIntrant($intrant);
            }

            $realisation->setDateRealisation(!empty($action['date_realisation']) ? new \DateTime($action['date_realisation']) : new \DateTime());
            $realisation->setQuantiteIntrant(isset($action['quantite_intrant']) ? (float)$action['quantite_intrant'] : null);
            $realisation->setUuidLocal($uuid);

            $em->persist($realisation);
            $syncedUuids[] = $uuid;
            $i++;

            // Traitement par lots pour éviter l'erreur 500 / Timeout
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear(); // Libère la mémoire des objets persistés
            }
        }

        try {
            $em->flush(); // Enregistre les restes
            $em->clear();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de l\'enregistrement final : ' . $e->getMessage()], 500);
        }

        return new JsonResponse([
            'status' => 'success',
            'synced_uuids' => $syncedUuids
        ], 200);
    }
}
