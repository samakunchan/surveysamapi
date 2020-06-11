<?php

namespace App\Tests\Controller;

use App\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ServeyControllerTest extends WebTestCase
{

    /**
     * @var KernelBrowser
     */
    private $client;

    /**
     * @var Survey|null
     */
    private $surveyEntity;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $allSurvey = $this->client->getContainer()->get('doctrine')->getRepository(Survey::class)->findAll();
        $lastObject = end($allSurvey);
        $this->surveyEntity = $this->client->getContainer()->get('doctrine')->getRepository(Survey::class)->findOneBy(['id' => $lastObject->getId()]);
    }

    /**
     * @test
     */
    public function listMethodAction(): void
    {
        $this->getEndPoint('/api/surveys', 'GET', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function showMethodAction(): void
    {
        $this->getEndPoint('/api/surveys/'.$this->surveyEntity->getId(), 'GET', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function createMethodAction(): void
    {
        $this->getEndPoint('/api/surveys', 'POST', Response::HTTP_CREATED, '{"title": "test"}');
    }

    /**
     * @test
     */
    public function editMethodAction(): void
    {
        $this->getEndPoint('/api/surveys/'.$this->surveyEntity->getId(), 'PUT', Response::HTTP_CREATED, '{"title": "test"}');
    }

    /**
     * @test
     */
    public function deleteMethodAction(): void
    {
        $this->getEndPoint('/api/surveys/'.$this->surveyEntity->getId(), 'DELETE', Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function wrongEndPoint()
    {
        $this->getEndPoint('/api/hello', 'GET', Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string $url
     * @param string $method
     * @param int $expectedStatusResponse
     * @param string|null $content
     */
    public function getEndPoint(string $url, string $method, int $expectedStatusResponse, string $content = null): void
    {
        $this->createAuthenticatedClient('sam@test.fr', '123456');

        if ($content) {
            $this->client->request($method, $url, [], [], [], $content);
        } else {
            $this->client->request($method, $url);
        }

        $this->assertResponseStatusCodeSame($expectedStatusResponse);
        if ($expectedStatusResponse !== 404) {
            $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
        }
    }

    /**
     * Create a client with a default Authorization header.
     * ALERT Dans la documenation il y a les underscores à enlever a username et password
     * @param string $username
     * @param string $password
     * @return KernelBrowser
     */
    protected function createAuthenticatedClient($username = 'user', $password = 'password')
    {
        $this->client->request(
            'POST', '/api/login_check', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
        $this->client->setServerParameter('HTTP_ACCEPT', sprintf('application/json'));
        $this->client->setServerParameter('HTTP_CONTENT_TYPE', sprintf('application/json; charset=UTF-8'));

        return $this->client;
    }
}
