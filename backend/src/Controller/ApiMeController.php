<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiMeController extends AbstractController
{
    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function getMe(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) return new JsonResponse(['error' => 'Non authentifié'], 401);

        $role = $user->getIdRole();

        return new JsonResponse([
            'permissions' => [
                'creationOuvrier' => (bool)$role->isCreationOuvrier(),
            ]
        ]);
    }
}
