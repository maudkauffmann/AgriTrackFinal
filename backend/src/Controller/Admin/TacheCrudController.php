<?php

namespace App\Controller\Admin;

use App\Entity\Tache;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TacheCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tache::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id_tache', 'ID')->hideOnForm(),
            TextField::new('nomTache', 'Nom de la tâche')
                ->setRequired(true)
                ->setHelp('Exemple : Désherbage, Récolte, Traitement...'),
        ];
    }
}
