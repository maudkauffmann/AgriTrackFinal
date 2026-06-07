<?php
namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\CrudFormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisableCsrfExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        // On désactive la protection CSRF uniquement pour EasyAdmin
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public static function getExtendedTypes(): iterable
    {
        // On cible uniquement les formulaires EasyAdmin
        return [CrudFormType::class];
    }
}
