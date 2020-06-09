<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
