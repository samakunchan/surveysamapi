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
        $this->client->request('/api/hello', 'GET');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string $url
     * @param string $method
     * @param int $expectedStatusResponse
     * @param string|null $content
     */
    public function getEndPoint(string $url, string $method, int $expectedStatusResponse, string $content = null): void
    {
        $server = [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_CONTENT_TYPE' => 'application/json; charset=UTF-8',
        ];
        if ($content) {
            $this->client->request($method, $url, [], [], $server, $content);
        } else {
            $this->client->request($method, $url, [], [], $server);
        }

        $this->assertResponseStatusCodeSame($expectedStatusResponse);
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
    }
}
