<?php

namespace App\Tests;

use App\Entity\Question;
use App\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AnswerControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client;

    /**
     * @var Survey|null
     */
    private $surveyEntity;

    /**
     * @var Question|null
     */
    private $questionEntity;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->surveyEntity = $this->client->getContainer()->get('doctrine')->getRepository(Survey::class)->findOneBy(['id' => 1]);
        $this->questionEntity = $this->client->getContainer()->get('doctrine')->getRepository(Question::class)->findOneBy(['id' => $this->surveyEntity->getId()]);
    }

    /**
     * @test
     */
    public function listMethodAction(): void
    {
        $this->getEndPoint(
            '/api/surveys/'.$this->surveyEntity->getId().'/questions/'.$this->questionEntity->getId().'/answers',
            'GET',
            Response::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function wrongEndPoint()
    {
        $this->getEndPoint(
            '/api/surveys/'.$this->surveyEntity->getId().'/questions/'.$this->questionEntity->getId().'/hello',
            'GET',
            Response::HTTP_NOT_FOUND
        );
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
        if ($expectedStatusResponse !== 404) {
            $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
        }
    }
}
