<?php

namespace App\Controller\Admin;

use App\Entity\Ouvrier;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OuvrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ouvrier::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if (!$this->isGranted('CREATE_OUVRIER')) {
            return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
        }

        return $actions;
    }

    public function createEntity(string $entityFqcn): Ouvrier
    {
        $ouvrier = new Ouvrier();
        /** @var Utilisateur $user */
        $user = $this->getUser();

        if ($user) {
            $ouvrier->setIdUtilisateur($user);
        }

        return $ouvrier;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('nomOuvrier', 'Nom Complet'),
            TextField::new('telOuvrier', 'Téléphone'),
            AssociationField::new('id_utilisateur', 'Créé par')
                ->onlyOnDetail(),
        ];
    }
}
