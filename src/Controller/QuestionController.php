<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Survey;
use App\Repository\QuestionRepository;
use App\Repository\SurveyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as nSecurity;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Class QuestionController
 * @package App\Controller
 * @Route("/api/surveys/{id}")
 * @ParamConverter("survey", options={"id" = "id"})
 */
class QuestionController extends AbstractController
{
    /**
     * @SWG\Tag(name="Question")
     * @SWG\Response(
     *     response=200,
     *     description="Response to a successful GET, PUT, PATCH or DELETE. Can also be used for a POST that doesn't result in a creation.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Question::class, groups={"question_show"}))
     *     )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="`Unauthorized`. When no or invalid authentication details are provided. Also useful to trigger an auth popup if the API is used from a browser Examples:`JWT Token not found` or`Expired JWT Token` or `Invalid JWT Token`.",
     * )
     * @SWG\Response(
     *     response=403,
     *     description="`Forbidden`. When authentication succeeded but authenticated user doesn't have access to the resource.",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="`Not Found`. When a non-existent resource is requested.",
     * )
     * @nSecurity(name="Bearer")
     *
     * @Route("/questions", name="question_list", methods={"GET"})
     *
     * @param Survey $survey
     * @param SurveyRepository $surveyRepository
     * @param CacheInterface $cache
     * @return JsonResponse
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function list(Survey $survey, SurveyRepository $surveyRepository, CacheInterface $cache): JsonResponse
    {
        $result = $cache->get('question_list_cache'.$survey->getId().$survey->getCreatedAt()->format('YmdHis'), function (ItemInterface $item) use ($survey, $surveyRepository) {
            $item->expiresAfter(10);
            return $this->json($surveyRepository->findBy(['id' => $survey->getId()]), Response::HTTP_OK, [], ['groups' => ['question_list']]);
        });

        if ($result) {
            return $result;
        }

        return $this->json($surveyRepository->findBy(['id' => $survey->getId()]), Response::HTTP_OK, [], ['groups' => ['question_list']]);
    }

    /**
     * @SWG\Tag(name="Question")
     * @SWG\Response(
     *     response=200,
     *     description="Response to a successful GET, PUT, PATCH or DELETE. Can also be used for a POST that doesn't result in a creation.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Question::class, groups={"question_show"}))
     *     )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="`Unauthorized`. When no or invalid authentication details are provided. Also useful to trigger an auth popup if the API is used from a browser Examples:`JWT Token not found` or`Expired JWT Token` or `Invalid JWT Token`.",
     * )
     * @SWG\Response(
     *     response=403,
     *     description="`Forbidden`. When authentication succeeded but authenticated user doesn't have access to the resource.",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="`Not Found`. When a non-existent resource is requested.",
     * )
     * @nSecurity(name="Bearer")
     *
     * @Route("/questions/{question_id}", name="question_show", methods={"GET"})
     *
     * @ParamConverter("question", options={"id" = "question_id"})
     * @param Question $question
     * @param QuestionRepository $questionRepository
     * @param CacheInterface $cache
     * @return JsonResponse
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function show(Question $question, QuestionRepository $questionRepository, CacheInterface $cache): JsonResponse
    {
        $result = $cache->get('question_show_cache'.$question->getId(), function (ItemInterface $item) use ($question, $questionRepository) {
            $item->expiresAfter(10);
            return $this->json($questionRepository->findBy(['id' => $question->getId()]), Response::HTTP_OK, [], ['groups' => ['question_show']]);
        });

        if ($result) {
            return $result;
        }

        return $this->json($questionRepository->findBy(['id' => $question->getId()]), Response::HTTP_OK, [], ['groups' => ['question_show']]);
    }
}
