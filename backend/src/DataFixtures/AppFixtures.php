<?php

namespace App\DataFixtures;

use App\Entity\Unite;
use App\Entity\TypeIntrant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // src/DataFixtures/AppFixtures.php
    public function load(ObjectManager $manager): void
    {
        $unites = ['Kilogramme (kg)', 'Litre (L)', 'Sac (50kg)', 'Unité (u)'];

        foreach ($unites as $u) {
            $existante = $manager->getRepository(Unite::class)->findOneBy(['nomUnite' => $u]);

            if (!$existante) {
                $unite = new Unite();
                $unite->setNomUnite($u);
                $manager->persist($unite);
            }
        }
        $manager->flush();
    }
}
