<?php

namespace App\Security\Voter;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AgriVoter extends Voter
{
    // On définit des "mots-clés" (attributs) pour chaque action
    public const CREATE_OUVRIER = 'CREATE_OUVRIER';
    public const CREATE_PARCELLE = 'CREATE_PARCELLE';
    public const CREATE_PLANTATION = 'CREATE_PLANTATION';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // On dit au voter de s'activer uniquement pour nos mots-clés
        return in_array($attribute, [self::CREATE_OUVRIER, self::CREATE_PARCELLE, self::CREATE_PLANTATION]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Si l'utilisateur n'est pas connecté,refuse tout
        if (!$user instanceof Utilisateur) {
            return false;
        }

        //récupère l'entité RoleUtilisateur liée à l'utilisateur
        $role = $user->getIdRole();
        if (!$role) {
            return false;
        }

        //vérifie le booléen en base de données selon l'action demandée
        return match($attribute) {
            self::CREATE_OUVRIER => $role->isCreationOuvrier(),
            self::CREATE_PARCELLE => $role->isCreationParcelle(),
            self::CREATE_PLANTATION => $role->isCreationPlantation(),
            default => false,
        };
    }
}
