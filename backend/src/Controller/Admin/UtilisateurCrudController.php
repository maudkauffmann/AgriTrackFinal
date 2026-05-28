<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id_utilisateur')->hideOnForm(),
            TextField::new('nomUtilisateur', 'Nom de l\'utilisateur'),
            TextField::new('telUtilisateur', 'Téléphone (Identifiant)'),
            EmailField::new('email', 'Email'),
            TextField::new('password', 'Mot de passe')->onlyOnForms(),
            AssociationField::new('id_role', 'Rôle attribué')
        ];
    }
}
