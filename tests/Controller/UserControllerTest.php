<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

// curl -X POST -H "Content-Type: application/json" http://127.0.0.1:9000/api/login_check -d '{"username":"sam@test.fr","password":"123456"}'
class UserControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function loginCheck()
    {
        $this->getEndPoint('/api/login_check','POST',Response::HTTP_OK);
    }

    /**
     * @param string $url
     * @param string $method
     * @param int $expectedStatusResponse
     */
    public function getEndPoint(string $url, string $method, int $expectedStatusResponse): void
    {
        $this->client->request($method, $url, [], [], []);
        $this->assertResponseStatusCodeSame($expectedStatusResponse);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        if ($expectedStatusResponse !== 404) {
            $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
        }
    }
}
