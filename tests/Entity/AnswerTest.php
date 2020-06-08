<?php

namespace App\Tests;

use App\Entity\Answer;
use PHPUnit\Framework\TestCase;

class AnswerTest extends TestCase
{
    /**
     * @test
     */
    public function sentence()
    {
        $sentence = 'lorem ipsum lorem ipsum.';
        $answer = new Answer();
        $answer->setSentence($sentence);

        $this->assertInstanceOf(Answer::class, $answer);
        $this->assertIsString($answer->getSentence());
        $this->assertStringContainsString('.', $sentence);
        $this->assertGreaterThan(5, strlen($sentence));
    }

    /**
     * @test
     */
    public function countAnswerSelected()
    {
        $answer = new Answer();
        $answer->setCountAnswer(1);

        $this->assertInstanceOf(Answer::class, $answer);
        $this->assertIsInt($answer->getCountAnswer());
    }
}
