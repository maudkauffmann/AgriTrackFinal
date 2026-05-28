<?php

namespace App\Controller\Admin;

use App\Entity\Campagne;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CampagneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Campagne::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Campagne Agricole')
            ->setEntityLabelInPlural('Campagnes Agricoles')
            ->setDefaultSort(['dateDeb' => 'DESC'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),

            TextField::new('nomCampagne', 'Nom de la Campagne')
                ->setHelp('Ex: Maïs Printemps 2024'),

            AssociationField::new('id_parcelle', 'Parcelle associée')
                ->setRequired(true),

            AssociationField::new('id_culture', 'Culture plantée')
                ->setRequired(true)
                ->setHelp('Choisissez le type de plante pour cette période'),

            DateField::new('dateDeb', 'Date de début'),
            DateField::new('dateFin', 'Date de fin prévue'),
        ];
    }
}
