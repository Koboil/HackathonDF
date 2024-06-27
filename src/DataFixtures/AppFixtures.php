<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $patient = new Patient();
            $patient->setGender($faker->randomElement(['male', 'female']));
            $patient->setSurname($faker->lastName);
            $patient->setName($faker->firstName);
            $patient->setBirthDate($faker->dateTimeBetween('-100 years', '-18 years'));
            $patient->setNote($faker->text);
            $patient->setPhoneNumber($faker->phoneNumber);

            $manager->persist($patient);
        }

        $manager->flush();
    }
}
