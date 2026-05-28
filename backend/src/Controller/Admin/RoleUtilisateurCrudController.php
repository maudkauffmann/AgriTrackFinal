<?php

namespace App\Controller\Admin;

use App\Entity\RoleUtilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class RoleUtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoleUtilisateur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Rôle Utilisateur')
            ->setEntityLabelInPlural('Rôles Utilisateurs')
            ->setDefaultSort(['nomRoleUser' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id_role', 'ID')->hideOnForm(),
            TextField::new('nomRoleUser', 'Nom du Rôle'),
            BooleanField::new('creationPlantation', 'Peut créer des plantations'),
            BooleanField::new('creationParcelle', 'Peut créer des parcelles'),
            BooleanField::new('creationOuvrier', 'Peut créer des ouvriers'),
            BooleanField::new('saisieTache', 'Peut saisir des tâches'),
            BooleanField::new('creationCampagne', 'Peut créer des campagnes'),
        ];
    }
}
