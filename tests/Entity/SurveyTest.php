<?php

namespace App\Tests\Entity;

use App\Entity\Question;
use App\Entity\Survey;
use DateTime;
use PHPUnit\Framework\TestCase;

class SurveyTest extends TestCase
{
    /**
     * @test
     */
    public function question()
    {
        $survey = new Survey();
        $question = new Question();
        $survey->addQuestion($question);

        $this->assertInstanceOf(Survey::class, $survey);
        $this->assertInstanceOf(Question::class, $question);
    }

    /**
     * @test
     */
    public function title()
    {
        $survey = new Survey();
        $survey->setTitle('lorem ipsum of Title Survey.');

        $this->assertInstanceOf(Survey::class, $survey);
        $this->assertIsString($survey->getTitle());
        $this->assertStringContainsString('.', $survey->getTitle());
        $this->assertGreaterThan(15, strlen($survey->getTitle()));
    }

    /**
     * @test
     */
    public function date()
    {
        $survey = new Survey();
        $survey->setCreatedAt(new DateTime());

        $this->assertInstanceOf(Survey::class, $survey);
        $this->assertInstanceOf(DateTime::class, $survey->getCreatedAt());
    }
}
