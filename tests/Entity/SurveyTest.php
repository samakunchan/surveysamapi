<?php

namespace App\Tests\Entity;

use App\Entity\Question;
use App\Entity\Survey;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class SurveyTest extends KernelTestCase
{
    /**
     * @test
     */
    public function isQuestionIsGood()
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
    public function isTitleIsGood()
    {
        $survey = new Survey();
        $survey->setTitle('lorem ipsum of Title Survey.');

        $this->assertInstanceOf(Survey::class, $survey);
        $this->assertIsString($survey->getTitle());
        $this->assertStringContainsString('.', $survey->getTitle());
        $this->assertGreaterThan(15, strlen($survey->getTitle()));
        $this->assertHasError($this->getEntity()->setTitle($survey->getTitle()), 0);
    }

    /**
     * @test
     */
    public function isDateIsGood()
    {
        $survey = new Survey();
        $survey->setCreatedAt(new DateTime());

        $this->assertInstanceOf(Survey::class, $survey);
        $this->assertInstanceOf(DateTime::class, $survey->getCreatedAt());
        $this->assertHasError($this->getEntity()->setCreatedAt($survey->getCreatedAt()), 0);
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
    public function isQuestionIsNotGood()
    {
        $survey = new Survey();
        $question = new Question();
        $survey->addQuestion($question);

        $this->assertNotInstanceOf(Survey::class, $question);
        $this->assertNotInstanceOf(Question::class, $survey);
    }

    /**
     * @test
     */
    public function isTitleIsNotGood()
    {
        $survey = new Survey();
        $survey->setTitle('lo');

        $this->assertNotInstanceOf('App\Entity\Question', $survey);
        $this->assertStringNotContainsString('#', $survey->getTitle());
        $this->assertLessThan(15, strlen($survey->getTitle()));
        $this->assertHasError($this->getBadEntity()->setTitle($survey->getTitle()), 1);
    }

    /**
     * @test
     */
    public function isEntityIsNotValid()
    {
        $this->assertHasError($this->getBadEntity(), 1);
    }

    /**
     * @return Survey
     */
    public function getEntity(): Survey
    {
        return (new Survey())
            ->setTitle('Il était une fois')
            ->setCreatedAt(new DateTime())
            ;
    }

    /**
     * @return Survey
     */
    public function getBadEntity(): Survey
    {
        return (new Survey())
            ->setTitle('Il')
            ;
    }

    /**
     * @param Survey $survey
     * @param $data
     */
    public function assertHasError(Survey $survey, $data)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($survey);
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
