<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
//
// NB: On ne peux pas test les booleans, car si on met nimp ça vaudra true et il est content.
//
class DefaultTest extends TestCase
{
    public function testExistingDirectories()
    {
        $this->assertDirectoryExists('src/Controller');
        $this->assertDirectoryExists('src/DataFixtures');
        $this->assertDirectoryExists('src/Entity');
        $this->assertDirectoryExists('src/Migrations');
        $this->assertDirectoryExists('src/Repository');
        $this->assertDirectoryDoesNotExist('src/Hello');
    }

    public function testExistingEntityFile()
    {
        $this->assertFileExists('src\Entity\Survey.php');
        $this->assertFileExists('src\Entity\Answer.php');
        $this->assertFileExists('src\Entity\Question.php');
        $this->assertFileExists('src\Entity\User.php');
    }

    public function testExistingRepositoryFile()
    {
        $this->assertFileExists('src\Repository\SurveyRepository.php');
        $this->assertFileExists('src\Repository\AnswerRepository.php');
        $this->assertFileExists('src\Repository\QuestionRepository.php');
        $this->assertFileExists('src\Repository\UserRepository.php');
    }
}
