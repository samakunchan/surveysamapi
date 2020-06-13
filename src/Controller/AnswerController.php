<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Answer;
use App\Repository\QuestionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as nSecurity;

/**
 * Class AnswerController
 * @package App\Controller
 * @Route("/api/surveys/{id}/questions/{question_id}")
 * @ParamConverter("survey", options={"id" = "id"})
 * @ParamConverter("question", options={"id" = "question_id"})
 */
class AnswerController extends AbstractController
{
    /**
     * @SWG\Tag(name="Answer")
     * @SWG\Response(
     *     response=200,
     *     description="Response to a successful GET, PUT, PATCH or DELETE. Can also be used for a POST that doesn't result in a creation.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Answer::class, groups={"answer_list"}))
     *     )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="`JWT Token not found` or`Expired JWT Token` or `Invalid JWT Token`",
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
     * @Route("/answers", name="answser_list", methods={"GET"})
     * @param Question $question
     * @param QuestionRepository $questionRepository
     * @return JsonResponse
     */
    public function list(Question $question, QuestionRepository $questionRepository): JsonResponse
    {
        return $this->json($questionRepository->findBy(['id' => $question->getId()]), Response::HTTP_OK, [], ['groups' => ['answer_list']]);
    }
}
