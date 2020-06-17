<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class UserTest extends KernelTestCase
{
    /**
     * @test
     */
    public function isEmailIsValid()
    {
        $user = new User();
        $user->setEmail('sam@test.fr');

        $this->assertInstanceOf('App\Entity\User', $user);
        $this->assertIsString($user->getEmail());
        $this->assertStringContainsString('@', $user->getEmail());
        $this->assertGreaterThan(8, strlen($user->getEmail()));
        $this->assertHasError($this->getEntity()->setEmail($user->getEmail()), 0);
    }

    /**
     * @test
     */
    public function isPasswordIsValid()
    {
        $user = new User();
        $user->setPassword('123456');

        $this->assertInstanceOf('App\Entity\User', $user);
        $this->assertIsString($user->getPassword());
        $this->assertGreaterThanOrEqual(6, strlen($user->getPassword()));
        $this->assertHasError($this->getEntity()->setPassword($user->getPassword()), 0);
    }

    /**
     * @test
     */
    public function isRoleIsValid()
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $this->assertInstanceOf('App\Entity\User', $user);
        $this->assertIsArray($user->getRoles());
        $this->assertSameSize(['ROLE_USER'], $user->getRoles());
        $this->assertHasError($this->getEntity()->setRoles($user->getRoles()), 0);
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
    public function isEmailIsNotValid()
    {
        $user = new User();
        $user->setEmail('sa@');

        $this->assertNotInstanceOf('App\Entity\Question', $user);
        $this->assertStringNotContainsString('#', $user->getEmail());
        $this->assertLessThan(8, strlen($user->getEmail()));
        $this->assertHasError($this->getBadEntity()->setEmail($user->getEmail()), 1);
    }

    /**
     * @test
     */
    public function isPasswordIsNotValid()
    {
        $user = new User();
        $user->setPassword('123');

        $this->assertNotInstanceOf('App\Entity\Question', $user);
        $this->assertLessThan(6, strlen($user->getPassword()));
    }

    /**
     * @test
     */
    public function isRoleIsNotValid()
    {
        $user = new User();
        $user->setRoles(['ROLE_']);

        $this->assertNotInstanceOf('App\Entity\Question', $user);
        $this->assertIsNotArray($user->getEmail());
        $this->assertNotSameSize(['ROLE_USER'], $user->getRoles());
    }

    /**
     * @test
     */
    public function isEntityIsNotValid()
    {
        $this->assertHasError($this->getBadEntity(), 1);
    }

    /**
     * @return User
     */
    public function getEntity(): User
    {
        return (new User())
            ->setEmail('sam@test.fr')
            ->setPassword('123456')
            ;
    }

    /**
     * @return User
     */
    public function getBadEntity(): User
    {
        return (new User())
            ->setEmail('sam')
            ->setPassword('123')
            ;
    }

    /**
     * @param User $user
     * @param $data
     */
    public function assertHasError(User $user, $data)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
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
