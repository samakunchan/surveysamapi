<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Repository\SurveyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SurveyController extends AbstractController
{
    /**
     * @Route("api/surveys", name="survey_list", methods={"GET"})
     * @param SurveyRepository $surveyRepository
     * @return JsonResponse
     */
    public function list(SurveyRepository $surveyRepository): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SurveyController.php',
        ]);
    }

    /**
     * @Route("api/surveys/{id}", name="survey_show", methods={"GET"})
     * @param Survey $survey
     * @return JsonResponse
     */
    public function show(Survey $survey)
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SurveyController.php',
        ]);
    }

    /**
     * @Route("api/surveys", name="survey_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SurveyController.php',
        ]);
    }

    /**
     * @Route("api/surveys/{id}", name="survey_edit", methods={"PUT"})
     * @param Survey $survey
     * @return JsonResponse
     */
    public function edit(Survey $survey)
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SurveyController.php',
        ]);
    }

    /**
     * @Route("api/surveys/{id}", name="survey_delete", methods={"DELETE"})
     * @param Survey $survey
     * @return JsonResponse
     */
    public function delete(Survey $survey)
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SurveyController.php',
        ]);
    }
}
