<?php

namespace App\Tests\Entity;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class AnswerTest extends KernelTestCase
{
    /**
     * @test
     */
    public function isSentenceIsGood()
    {
        $sentence = 'lorem ipsum lorem ipsum.';
        $answer = new Answer();
        $answer->setSentence($sentence);

        $this->assertInstanceOf(Answer::class, $answer);
        $this->assertIsString($answer->getSentence());
        $this->assertStringContainsString('.', $sentence);
        $this->assertGreaterThanOrEqual(3, strlen($sentence));
        $this->assertHasError($this->getEntity()->setSentence($sentence), 0);
    }

    /**
     * @test
     */
    public function isCountAnswerSelectedGood()
    {
        $answer = new Answer();
        $answer->setCountAnswer(1);

        $this->assertInstanceOf(Answer::class, $answer);
        $this->assertIsInt($answer->getCountAnswer());
        $this->assertHasError($this->getEntity()->setCountAnswer(1), 0);
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
        $sentence = 'a.';
        $answer = new Answer();
        $answer->setSentence($sentence);

        $this->assertNotInstanceOf(Question::class, $answer);
        $this->assertIsString($answer->getSentence());
        $this->assertStringContainsString('.', $sentence);
        $this->assertLessThan(3, strlen($sentence));
        $this->assertHasError($this->getEntity()->setSentence($sentence), 1);
    }

    /**
     * @test
     */
    public function isEntityIsNotValid()
    {
        $this->assertHasError($this->getBadEntity(), 1);
    }

    /**
     * @return Answer
     */
    public function getEntity(): Answer
    {
        return (new Answer())
            ->setSentence('Il était une fois')
            ->setCountAnswer(0)
            ;
    }

    /**
     * @return Answer
     */
    public function getBadEntity(): Answer
    {
        return (new Answer())
            ->setSentence('Il')
            ;
    }

    /**
     * @param Answer $answer
     * @param $data
     */
    public function assertHasError(Answer $answer, $data)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($answer);
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
