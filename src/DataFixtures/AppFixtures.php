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
        $listeQuestions = [
            'bonjour, que se passe-t-il?',
            'Un accompagnant est obligatoire pour votre sortie',
            'CH de Gonesse : suite à votre opÃ©ration, si vous rencontrez des signes anormaux (douleurs, saignements,...) répondez SIG',
            'Merci le service va vous contacter.',
            'Pour toute question ou problème vous pouvez appeler la chirurgie ambulatoire au 01.00.00.00.00 du lundi au vendredi de 8h à 18h. En dehors de ces horaires, le weekend et jours fériés, contactez les numÃ©ros indiqués sur la pochette de sortie',
            'Vous n\'avez pas répondu au dernier message. Si tout va bien, répondez TVB',
            'Merci de remplir le questionnaire médical et d\'apporter la copie de vos dernières ordonnances',
            'Suite à votre intervention à l\'Institut Mutualiste Montsouris si tout va bien (pas de douleur, pas de saignement, pas d\'hématome), répondez TVB',
            'Pouvez-vous nous indiquer le type de difficulté que vous avez rencontrée ?',
            'Au cours des 7 derniers jours, si vous avez eu un de ces signes, répondez seulement OUI, Sinon répondez seulement NON : - Fièvre, sensation de grippe ou courbatures, toux ou essoufflement inhabituel, mal de tete inhabituel ou perte de l\'odorat ou du gout'
        ];
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
            $question->setQuestion($faker->randomElement($listeQuestions));
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
