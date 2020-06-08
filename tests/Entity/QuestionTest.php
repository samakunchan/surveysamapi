<?php

namespace App\Tests\Entity;

use App\Entity\Answer;
use App\Entity\Question;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    /**
     * @test
     */
    public function something()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function sentence()
    {
        $sentence = 'lorem ipsum lorem ipsum ?';
        $question = new Question();
        $question->setSentence($sentence);

        $this->assertInstanceOf(Question::class, $question);
        $this->assertIsString($question->getSentence());
        $this->assertStringContainsString('?', $sentence);
        $this->assertGreaterThan(15, strlen($sentence));
    }

    /**
     * @test
     */
    public function status()
    {
        $sentence = 'Complete';
        $question = new Question();
        $question->setStatus($sentence);

        $this->assertInstanceOf(Question::class, $question);
        $this->assertIsString($question->getStatus());
        $this->assertStringContainsString('Complete', $sentence);
    }

    /**
     * @test
     */
    public function answer()
    {
        $question = new Question();
        $answer = new Answer();
        $question->addAnswer($answer);

        $this->assertInstanceOf(Question::class, $question);
        $this->assertInstanceOf(Answer::class, $answer);
    }
}
