<?php

namespace App\Tests\Controller;

use App\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

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
        $this->surveyEntity = $this->client->getContainer()->get('doctrine')->getRepository(Survey::class)->findOneBy(['id' => 1]);
    }

    /**
     * @test
     */
    public function getAction(): void
    {
        $this->endPoint('/api/surveys', 'GET');
    }

    /**
     * @test
     */
    public function postAction(): void
    {
        $this->endPoint('/api/surveys', 'POST');
    }

    /**
     * @test
     */
    public function showAction(): void
    {
        $this->endPoint('/api/surveys/'.$this->surveyEntity->getId(), 'GET');
    }

    /**
     * @test
     */
    public function putAction(): void
    {
        $this->endPoint('/api/surveys/'.$this->surveyEntity->getId(), 'PUT');
    }

    /**
     * @test
     */
    public function deleteAction(): void
    {
        $this->endPoint('/api/surveys/'.$this->surveyEntity->getId(), 'DELETE');
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
     */
    public function endPoint(string $url, string $method): void
    {
        $this->client->request($method, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
    }
}
