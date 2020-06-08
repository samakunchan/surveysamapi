<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Survey;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SurveyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        for($i = 0; $i < 8; $i++ ){
            $survey = new Survey();
            $question = new Question();
            $question->setSentence($faker->sentence.'?');
            $question->setStatus($faker->randomElement(['Pending', 'Complete']));

            for($j = 0; $j < $faker->numberBetween(2, 5); $j++ ){
                $answer = new Answer();
                $answer->setSentence($faker->sentence);
                $answer->setCountAnswer($faker->numberBetween(0, 20));
                $survey->addAnswer($answer);
                $manager->persist($survey);
            }
            $survey->setQuestion($question);
            $manager->persist($survey);
        }

        $manager->flush();
    }
}
