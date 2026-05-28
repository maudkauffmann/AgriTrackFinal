<?php

namespace App\Controller\Admin;

use App\Controller\Admin\CampagneCrudController;
use App\Controller\Admin\PlantationCrudController;
use App\Controller\Admin\ParcelleCrudController;
use App\Controller\Admin\OuvrierCrudController;
use App\Controller\Admin\RealiserCrudController;
use App\Entity\Culture;
use App\Entity\RoleUtilisateur;
use App\Entity\Utilisateur;
use App\Repository\TacheRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(PlantationCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AgriTrack Admin')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');

        yield MenuItem::section('Exploitation');

        yield MenuItem::linkTo(PlantationCrudController::class, 'Plantations', 'fas fa-leaf');
        yield MenuItem::linkTo(ParcelleCrudController::class, 'Parcelles', 'fas fa-map');
        yield MenuItem::linkTo(CampagneCrudController::class, 'Campagnes', 'fas fa-calendar-alt');
        yield MenuItem::linkTo(CultureCrudController::class, 'Cultures', 'fas fa-calendar-alt');
        yield MenuItem::section('Personnel & Tâches');
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkTo(UtilisateurCrudController::class, 'Utilisateurs', 'fa fa-user-gear');
            yield MenuItem::linkTo(RoleUtilisateurCrudController::class, 'Rôles', 'fa fa-tags');
        }
        yield MenuItem::linkTo(OuvrierCrudController::class, 'Ouvriers', 'fas fa-users');
        yield MenuItem::linkTo(TacheCrudController::class, 'Taches', 'fas fa-folder');
    }
}
