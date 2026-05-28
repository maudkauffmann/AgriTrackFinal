<?php

namespace App\Controller\Admin;

use App\Entity\Culture;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CultureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Culture::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),

            TextField::new('nomCulture', 'Nom de la culture')
                ->setHelp('Exemple: Maïs, Cacao, Soja...'),

            AssociationField::new('id_tp_culture', 'Catégorie de culture')
                ->setHelp('Exemple: Céréale, Culture pérenne...')
        ];
    }
}
