<?php

namespace App\Controller\Admin;

use App\Entity\TypeIntrant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class TypeIntrantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeIntrant::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Type d\'intrant')
            ->setEntityLabelInPlural('Types d\'intrants')
            ->setDefaultSort(['nomTpIntrant' => 'ASC'])
            ->setFormOptions([
            'attr' => [
                'data-controller' => ''
            ]
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('nomTpIntrant', 'Nom du type'),
        ];
    }
}
