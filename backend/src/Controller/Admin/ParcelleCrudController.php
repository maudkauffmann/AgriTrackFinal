<?php

namespace App\Controller\Admin;

use App\Entity\Parcelle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ParcelleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Parcelle::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $voirCampagnes = Action::new('voirCampagnes', 'Campagnes', 'fa fa-seedling')
            ->linkToUrl(function (Parcelle $parcelle) {
                return $this->container->get(AdminUrlGenerator::class)
                    ->setController(CampagneCrudController::class)
                    ->setAction(Action::INDEX)
                    ->set('filters[id_parcelle][value]', $parcelle->getId())
                    ->set('filters[id_parcelle][comparison]', '=')
                    ->generateUrl();
            })
            ->addCssClass('btn btn-outline-success');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $voirCampagnes)
            ->add(Crud::PAGE_DETAIL, $voirCampagnes);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('id_plantation');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('nomParcelle', 'Nom de la parcelle'),
            NumberField::new('superficieParc', 'Superficie (ha)'),
            AssociationField::new('id_plantation', 'Plantation liée')
                ->setRequired(true),
            IntegerField::new('nbCampagnes', 'Nombre Total Campagnes')
                ->onlyOnIndex(),

            TextField::new('culturesActuelles', 'Cultures en cours')
                ->onlyOnIndex()
                ->setHelp('Basé sur les dates de début et de fin des campagnes'),

            ArrayField::new('campagnes', 'Historique des cultures')
                ->onlyOnDetail(),
        ];
    }
}
