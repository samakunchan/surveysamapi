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
        $allSurvey = $this->client->getContainer()->get('doctrine')->getRepository(Survey::class)->findAll();
        $lastObject = end($allSurvey);
        $this->surveyEntity = $this->client->getContainer()->get('doctrine')->getRepository(Survey::class)->findOneBy(['id' => $lastObject->getId()]);
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
     */
    public function getEndPoint(string $url, string $method, int $expectedStatusResponse): void
    {
        $this->createAuthenticatedClient('sam@test.fr', '123456');
        $this->client->request($method, $url);
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
