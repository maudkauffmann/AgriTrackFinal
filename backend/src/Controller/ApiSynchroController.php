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
        if ($request->getMethod() === 'OPTIONS') return new JsonResponse(null, 200);

        $payload = json_decode($request->getContent(), true);
        if (!$payload || !isset($payload['actions'])) return new JsonResponse(['error' => 'Données invalides'], 400);

        $syncedUuids = [];
        foreach ($payload['actions'] as $action) {
            $uuid = $action['uuid'] ?? null;
            if (!$uuid || $em->getRepository(Realiser::class)->findOneBy(['uuidLocal' => $uuid])) continue;

            $tache = isset($action['tache_id']) ? $em->getRepository(Tache::class)->find($action['tache_id']) : null;
            $campagne = isset($action['campagne_id']) ? $em->getRepository(Campagne::class)->find($action['campagne_id']) : null;

            // Logique de création d'ouvrier à la volée
            $ouvrier = null;
            if (!empty($action['ouvrier_id']) && $action['ouvrier_id'] !== 'null') {
                $ouvrier = $em->getRepository(Ouvrier::class)->find($action['ouvrier_id']);
            } elseif (!empty($action['ouvrier_nom'])) {
                $ouvrier = new Ouvrier();
                $ouvrier->setNomOuvrier($action['ouvrier_nom']);
                $ouvrier->setTelOuvrier($action['ouvrier_tel'] ?? 'N/A');
                $em->persist($ouvrier);
                $em->flush(); // Flush immédiat pour obtenir l'ID
            }

            if (!$tache || !$ouvrier || !$campagne) continue;

            $realisation = new Realiser();
            $realisation->setTache($tache);
            $realisation->setOuvrier($ouvrier);
            $realisation->setCampagne($campagne);
            $realisation->setUuidLocal($uuid);
            $realisation->setDateRealisation(!empty($action['date_realisation']) ? new \DateTime($action['date_realisation']) : new \DateTime());
            $realisation->setQuantiteIntrant(isset($action['quantite_intrant']) ? (float)$action['quantite_intrant'] : null);

            if (!empty($action['intrant_id']) && $action['intrant_id'] !== '0') {
                $realisation->setIntrant($em->getRepository(Intrant::class)->find($action['intrant_id']));
            }

            $em->persist($realisation);
            $syncedUuids[] = $uuid;
        }

        $em->flush();
        return new JsonResponse(['status' => 'success', 'synced_uuids' => $syncedUuids], 200);
    }
}
