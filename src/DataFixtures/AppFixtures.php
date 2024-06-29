<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\Patient;
use App\Entity\Questions;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $patients = [];
        for ($i = 0; $i < 100; $i++) {
            $patient = new Patient();
            $patient->setGender($faker->randomElement(['male', 'female']));
            $patient->setSurname($faker->lastName);
            $patient->setName($faker->firstName);
            $patient->setBirthDate($faker->dateTimeBetween('-100 years', '-18 years'));
            $patient->setPhoneNumber(0000000000);

            $manager->persist($patient);
            $patients[] = $patient;
        }

        $statusCreated = false;
        $i= 0;
        foreach ($patients as $patient) {
            $question = new Questions();
            $question->setPatient($patient);
            $question->setQuestion("Question " . $faker->sentence);
            $question->setDate($faker->dateTimeBetween('-1 years', 'now'));
            $manager->persist($question);

            $message = new Message();
            $message->setPatientId($patient);
            $message->setQuestionId($question);
            $message->setResponse("Reponse " . $faker->sentence);
            $message->setDate($faker->dateTimeBetween('-1 years', 'now'));
            $manager->persist($message);

            $status = new Status();
            $status->setPatientId($patient);

            if ($i < 10){
                $status->setBubbleStatus($faker->randomElement(['bleu', 'gris']));
                $i++;
            }else{
                $status->setBubbleStatus($faker->randomElement(['jaune','orange']));

            }

            if($status->getBubbleStatus() == 'bleu' || $status->getBubbleStatus() == 'gris'){
                    $status->setType('pas supporter');
            }else{
                    $status->setType($faker->randomElement(['ok','leger','modere','critique']));
            }
            $status->setSendSms($faker->randomElement([true, false]));
            $status->setActive($faker->randomElement([true, false]));

            $manager->persist($status);


        }

        $manager->flush();
    }
}
