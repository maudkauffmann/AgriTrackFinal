<?php

namespace App\Controller\Admin;

use App\Entity\Plantation;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\SecurityBundle\Security;

class PlantationCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Plantation::class;
    }

    public function createEntity(string $entityFqcn): Plantation
    {
        $plantation = new Plantation();
        /** @var Utilisateur|null $user */
        $user = $this->security->getUser();
        if ($user) {
            $plantation->setIdUtilisateur($user);
        }
        return $plantation;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Plantation')
            ->setEntityLabelInPlural('Plantations')
            ->showEntityActionsInlined()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $voirParcelles = Action::new('voirParcelles', 'Parcelles', 'fa fa-map-marker')
            ->linkToUrl(function (Plantation $plantation) {
                return $this->container->get(AdminUrlGenerator::class)
                    ->setController(ParcelleCrudController::class)
                    ->setAction(Action::INDEX)
                    ->set('filters[id_plantation][value]', $plantation->getId())
                    ->set('filters[id_plantation][comparison]', '=')
                    ->generateUrl();
            })
            ->addCssClass('btn btn-outline-info');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $voirParcelles)
            ->add(Crud::PAGE_DETAIL, $voirParcelles)

            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel('Consulter')->addCssClass('btn btn-primary');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->setLabel('Modifier');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) {
                return $action->setLabel('Modifier cette plantation')->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_DETAIL, Action::INDEX, function (Action $action) {
                return $action->setLabel('Retour à la liste')->setIcon('fa fa-list');
            })
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('nomPlantation', 'Nom de la plantation'),
            TextField::new('ville', 'Ville / Localité'),
            NumberField::new('longitude')->setColumns('col-6')->hideOnIndex(),
            NumberField::new('latitude')->setColumns('col-6')->hideOnIndex(),
            TextareaField::new('indications', 'Indications pour trouver le terrain')
                ->hideOnIndex()
                ->setFormTypeOptions(['attr' => ['rows' => 4]]),
        ];
    }
}
