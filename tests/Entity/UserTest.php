<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function email()
    {
        $user = new User();
        $user->setEmail('sam@test.fr');

        $this->assertInstanceOf('App\Entity\User', $user);
        $this->assertIsString($user->getEmail());
        $this->assertStringContainsString('@', $user->getEmail());
        $this->assertGreaterThan(8, strlen($user->getEmail()));
    }

    /**
     * @test
     */
    public function password()
    {
        $user = new User();
        $user->setPassword('123456');

        $this->assertInstanceOf('App\Entity\User', $user);
        $this->assertIsString($user->getPassword());
        $this->assertGreaterThanOrEqual(6, strlen($user->getPassword()));
    }

    /**
     * @test
     */
    public function role()
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $this->assertInstanceOf('App\Entity\User', $user);
        $this->assertIsArray($user->getRoles());
        $this->assertSameSize(['ROLE_USER'], $user->getRoles());
    }
}
