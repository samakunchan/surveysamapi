<?php


namespace App\Tests\DataFixtures;

use App\DataFixtures\SurveyFixtures;
use App\Repository\SurveyRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class SurveyFixturesTest extends KernelTestCase
{

    use FixturesTrait;

    public function testNumberOfSurvey()
    {
        self::bootKernel();
        $this->loadFixtures([SurveyFixtures::class]);
        $survey = self::$container->get(SurveyRepository::class)->count([]);
        self::assertEquals(8, $survey);
    }
}
