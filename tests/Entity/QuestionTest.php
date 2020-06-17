<?php

namespace App\Tests\Entity;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class QuestionTest extends KernelTestCase
{
    /**
     * @test
     */
    public function isSentenceIsGood()
    {
        $sentence = 'lorem ipsum lorem ipsum ?';
        $question = new Question();
        $question->setSentence($sentence);

        $this->assertInstanceOf(Question::class, $question);
        $this->assertIsString($question->getSentence());
        $this->assertStringContainsString('?', $sentence);
        $this->assertGreaterThan(15, strlen($sentence));
        $this->assertHasError($this->getEntity()->setSentence($sentence), 0);
    }

    /**
     * @test
     */
    public function isStatusIsGood()
    {
        $sentence = 'Complete';
        $question = new Question();
        $question->setStatus($sentence);

        $this->assertInstanceOf(Question::class, $question);
        $this->assertIsString($question->getStatus());
        $this->assertStringContainsString('Complete', $sentence);
        $this->assertHasError($this->getEntity()->setSentence($sentence), 0);
    }

    /**
     * @test
     */
    public function isAnswerIsGood()
    {
        $question = new Question();
        $answer = new Answer();
        $question->addAnswer($answer);

        $this->assertInstanceOf(Question::class, $question);
        $this->assertInstanceOf(Answer::class, $answer);
    }

    /**
     * @test
     */
    public function isEntityIsValid()
    {
        $this->assertHasError($this->getEntity(), 0);
    }

    /**
     * @test
     */
    public function isSentenceIsNotGood()
    {
        $sentence = 'lore';
        $question = new Question();
        $question->setSentence($sentence);

        $this->assertNotInstanceOf('App\Entity\Answer', $question);
        $this->assertIsString($question->getSentence());
        $this->assertStringNotContainsString('#', $sentence);
        $this->assertLessThan(15, strlen($sentence));
        $this->assertHasError($this->getBadEntity()->setSentence($sentence), 1);
    }

    /**
     * @test
     */
    public function isStatusIsNotGood()
    {
        $sentence = 1;
        $question = new Question();
        $question->setStatus($sentence);

        $this->assertNotInstanceOf('App\Entity\Answer', $question);
        $this->assertStringNotContainsString('yolo', $sentence);
        $this->assertHasError($this->getBadEntity()->setSentence($sentence), 2);
    }


    /**
     * @test
     */
    public function isAnswerIsNotGood()
    {
        $question = new Question();
        $answer = new Answer();
        $question->addAnswer($answer);

        $this->assertNotInstanceOf(Question::class, $answer);
        $this->assertNotInstanceOf(Answer::class, $question);
    }

    /**
     * @test
     */
    public function isEntityIsNotValid()
    {
        $this->assertHasError($this->getBadEntity(), 2);
    }

    /**
     * @return Question
     */
    public function getEntity(): Question
    {
        return (new Question())
            ->setSentence('Il était une fois')
            ->setStatus('complete')
            ;
    }

    /**
     * @return Question
     */
    public function getBadEntity(): Question
    {
        return (new Question())
            ->setSentence('Il')
            ->setStatus('hello')
            ;
    }

    /**
     * @param Question $question
     * @param $data
     */
    public function assertHasError(Question $question, $data)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($question);
        $messages = [];
        /**
         * @var ConstraintViolation $error
         */
        foreach ($errors as $error){
            $messages[] = $error->getPropertyPath(). ' => '.$error->getMessage();
        }
        $this->assertCount($data, $errors, implode(', ', $messages));
    }
}
