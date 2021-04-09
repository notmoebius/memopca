<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // on crée 4 auteurs avec noms et prénoms "aléatoires" en français
        $user = Array();
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user ->setStatus($faker->sentence($nbWords = 2, $variableNbWords = true));
            $user->setMobilenumber('0684692105');
            $user->setPhonenumber('0471065586');
            $user->setStructure($faker->sentence($nbWords = 2, $variableNbWords = true));
            $user->setFloor('Etage 2');
            $user->setRole('1');
            $user->setGrade($faker->boolean);

            $manager->persist($user);

        $manager->flush();
        }
    }
}