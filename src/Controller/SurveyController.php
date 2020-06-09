<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Repository\SurveyRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
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
        return $this->json($surveyRepository->findAll(), Response::HTTP_OK, [], ['groups' => ['survey_list']]);
    }

    /**
     * @Route("api/surveys/{id}", name="survey_show", methods={"GET"})
     * @param Survey $survey
     * @return JsonResponse
     */
    public function show(Survey $survey): JsonResponse
    {
        return $this->json($survey, Response::HTTP_OK, [], ['groups' => ['survey_show']]);
    }

    /**
     * @Route("api/surveys", name="survey_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        try {
            $datas = $request->getContent();
            $survey = $serializer->deserialize($datas, Survey::class, 'json');
            $errors = $validator->validate($survey);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }
            $survey->setCreatedAt(new DateTime());
            $entityManager->persist($survey);
            $entityManager->flush();
            return $this->json(
                $survey,
                Response::HTTP_CREATED,
                ['location' => $this->generateUrl('survey_show', ['id' => $survey->getId()], UrlGeneratorInterface::ABSOLUTE_URL)],
                ['groups' => ['survey_show']]
            );
        } catch (NotEncodableValueException $e) {
            return $this->json(
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("api/surveys/{id}", name="survey_edit", methods={"PUT"})
     * @param Request $request
     * @param Survey $survey
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function edit(Request $request, Survey $survey, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator) :JsonResponse
    {
        $datas = $request->getContent();
        try {
            $survey = $serializer->deserialize($datas, Survey::class, 'json', ['object_to_populate' => $survey]);
            $errors = $validator->validate($survey);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }
            $survey->setCreatedAt($survey->getCreatedAt());
            $entityManager->persist($survey);
            $entityManager->flush();
            return $this->json($survey, Response::HTTP_CREATED, [], ['groups' => ['survey_show']]);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("api/surveys/{id}", name="survey_delete", methods={"DELETE"})
     * @param Survey $survey
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(Survey $survey, EntityManagerInterface $entityManager)
    {
        try {
            $entityManager->remove($survey);
            $entityManager->flush();
            return $this->json($survey, Response::HTTP_OK, [], ['groups' => ['survey_show']]);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
