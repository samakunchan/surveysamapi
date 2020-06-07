<?php

namespace App\Tests\Entity;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Survey;
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
        $survey->setQuestion($question);

        $this->assertInstanceOf('App\Entity\Survey', $survey);
        $this->assertInstanceOf('App\Entity\Question', $question);
    }

    /**
     * @test
     */
    public function answer()
    {
        $survey = new Survey();
        $answer = new Answer();
        $survey->addAnswer($answer);

        $this->assertInstanceOf('App\Entity\Survey', $survey);
        $this->assertInstanceOf('App\Entity\Answer', $answer);
    }
}
