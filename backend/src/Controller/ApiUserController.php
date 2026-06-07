<?php

namespace App\Controller;

use App\Entity\RoleUtilisateur;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ApiUserController extends AbstractController
{
    #[Route('/user/add', name: 'user_add', methods: ['POST'])]
    public function add(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['message' => 'Données JSON invalides'], 400);
        }

        try {
            $user = new Utilisateur();
            $user->setNomUtilisateur($data['nom'] ?? '');
            $user->setEmail($data['email'] ?? '');
            $tel = preg_replace('/[^0-9]/', '', $data['tel'] ?? '');
            $user->setTelUtilisateur($tel);
            $user->setPassword($hasher->hashPassword($user, $data['mdp'] ?? ''));

            $roleRepo = $em->getRepository(RoleUtilisateur::class);
            $typeDemande = $data['roleType'] ?? 'SUPERVISEUR';

            $role = $roleRepo->findOneBy(['nomRoleUser' => $typeDemande]);

            if (!$role) {
                return new JsonResponse(['message' => "Le rôle $typeDemande n'existe pas en base."], 400);
            }

            $user->setIdRole($role);
            $em->persist($user);
            $em->flush();

            return new JsonResponse([
                'status' => 'success',
                'user' => [
                    'nom' => $user->getNomUtilisateur(),
                    'role' => $role->getNomRoleUser()
                ]
            ], 201);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/user/me', name: 'user_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        // DEBUG : Si vous obtenez null ici, votre token JWT n'est pas reconnu
        if (!$user) {
            return new JsonResponse(['message' => 'Non authentifié'], 401);
        }

        return new JsonResponse([
            'nomUtilisateur' => $user->getNomUtilisateur(),
            'telUtilisateur' => $user->getTelUtilisateur()
        ]);
    }

    #[Route('/user/update', name: 'user_update', methods: ['PATCH'])]
    public function update(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): JsonResponse
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        // Mise à jour du nom
        if (isset($data['nomUtilisateur'])) {
            $user->setNomUtilisateur($data['nomUtilisateur']);
        }

        // Mise à jour du téléphone
        if (isset($data['telUtilisateur'])) {
            $user->setTelUtilisateur($data['telUtilisateur']);
        }

        // Mise à jour du mot de passe (si présent)
        if (!empty($data['newPassword'])) {
            $user->setPassword($hasher->hashPassword($user, $data['newPassword']));
        }

        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'user' => [
                'nomUtilisateur' => $user->getNomUtilisateur(),
                'telUtilisateur' => $user->getTelUtilisateur()
            ]
        ]);
    }
}
