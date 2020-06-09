<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Survey;
use App\Repository\QuestionRepository;
use App\Repository\SurveyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QuestionController
 * @package App\Controller
 * @Route("/api/surveys/{id}")
 * @ParamConverter("survey", options={"id" = "id"})
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/questions", name="question_list", methods={"GET"})
     * @param Survey $survey
     * @param SurveyRepository $surveyRepository
     * @return JsonResponse
     */
    public function list(Survey $survey, SurveyRepository $surveyRepository): JsonResponse
    {
        return $this->json($surveyRepository->findBy(['id' => $survey->getId()]), Response::HTTP_OK, [], ['groups' => ['question_list']]);
    }

    /**
     * @Route("/questions/{question_id}", name="question_show", methods={"GET"})
     * @ParamConverter("question", options={"id" = "question_id"})
     * @param Question $question
     * @param QuestionRepository $questionRepository
     * @return JsonResponse
     */
    public function show(Question $question, QuestionRepository $questionRepository): JsonResponse
    {
        return $this->json($questionRepository->findBy(['id' => $question->getId()]), Response::HTTP_OK, [], ['groups' => ['question_show']]);
    }
}
