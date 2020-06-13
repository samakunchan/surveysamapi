<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Repository\SurveyRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use JsonLd\Context;
use MK\HAL\HALLink;
use MK\HAL\HALObject;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as nSecurity;

/**
 * Class SurveyController
 * @package App\Controller
 */
class SurveyController extends AbstractController
{
    /**
     * @SWG\Tag(name="Survey")
     * @SWG\Response(
     *     response=200,
     *     description="Response to a successful GET, PUT, PATCH or DELETE. Can also be used for a POST that doesn't result in a creation.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Survey::class, groups={"survey_list"}))
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
     * Pour les liens: https://github.com/Mo0812/MKHal
     * Pour le context: https://github.com/Torann/json-ld
     * @Route("api/surveys", name="survey_list", methods={"GET"})
     * @param SurveyRepository $surveyRepository
     * @return JsonResponse
     */
    public function list(SurveyRepository $surveyRepository): JsonResponse
    {
        $context = $this->getContext();
        // array_merge va servir a fusionner l'object créé avec pour le context et le fetchAll... et ça fait propre quand on utilise postman
        $datas = array_merge(
            $context->getProperties(),
            ['nb_results' => count($surveyRepository->findAll())],
            ['values' => $surveyRepository->findAll()]
        );
        return $this->json($datas, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => ['survey_list']]);
    }

    /**
     * @SWG\Tag(name="Survey")
     * @SWG\Parameter(
     *     name="id",
     *     description="Survey id",
     *     in="path",
     *     required=true,
     *     type="integer"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Response to a successful GET, PUT, PATCH or DELETE. Can also be used for a POST that doesn't result in a creation.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Survey::class, groups={"survey_list"}))
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
     * @Route("api/surveys/{id}", name="survey_show", methods={"GET"})
     * @param Survey $survey
     * @return JsonResponse
     */
    public function show(Survey $survey): JsonResponse
    {
        $url = $this->generateUrl('survey_show', ['id'=> $survey->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $hal = new HALObject($url);


        $hal->addData(['survey' => $survey]);
        $hal->addLink('update', new HALLink($url));
        $hal->addLink('delete', new HALLink($url));
        return $this->json($hal, Response::HTTP_OK, [], ['groups' => ['survey_show']]);
    }

    /**
     * @SWG\Tag(name="Survey")
     * @SWG\Response(
     *    response=200,
     *    description="Response to a successful GET, PUT, PATCH or DELETE. Can also be used for a POST that doesn't result in a creation.",
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(ref=@Model(type=Survey::class, groups={"survey_list"}))
     *     )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="`Created`. Response to a POST that results in a creation. Should be combined with a Location header pointing to the location of the new resource.",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Survey::class, groups={"survey_list"}))
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
     * @SWG\Tag(name="Survey")
     * @SWG\Response(
     *    response=200,
     *    description="Response to a successful GET, PUT, PATCH or DELETE. Can also be used for a POST that doesn't result in a creation.",
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(ref=@Model(type=Survey::class, groups={"survey_list"}))
     *     )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="`Created`. Response to a POST that results in a creation. Should be combined with a Location header pointing to the location of the new resource.",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=Survey::class, groups={"survey_list"}))
     *     )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="`Unauthorized`. When no or invalid authentication details are provided. Also useful to trigger an auth popup if the API is used from a browser Examples:`JWT Token not found` or`Expired JWT Token` or `Invalid JWT Token`",
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
     * @Route("api/surveys/{id}", name="survey_edit", methods={"PUT"})
     * @param Request $request
     * @param Survey $survey
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function edit(Request $request, Survey $survey, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $datas = $request->getContent();
        try {
            $surveySerialized = $serializer->deserialize($datas, Survey::class, 'json', ['object_to_populate' => $survey]);
            $errors = $validator->validate($surveySerialized);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }
            $surveySerialized->setCreatedAt($surveySerialized->getCreatedAt());
            $entityManager->persist($surveySerialized);
            $entityManager->flush();
            return $this->json($surveySerialized, Response::HTTP_CREATED, [], ['groups' => ['survey_show']]);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @SWG\Tag(name="Survey")
     * @SWG\Response(
     *    response=204,
     *    description="`No Content`. Response to a successful request that won't be returning a body (like a DELETE request).",
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(ref=@Model(type=Survey::class, groups={"survey_list"}))
     *     )
     * )
     * @nSecurity(name="Bearer")
     *
     * @Route("api/surveys/{id}", name="survey_delete", methods={"DELETE"})
     * @param Survey $survey
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(Survey $survey, EntityManagerInterface $entityManager): JsonResponse
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

    /**
     * @return Context
     */
    private function getContext(): Context
    {
        return Context::create('organization', [
            'name' => 'Samakunchan Technology',
            'description' => 'Développeur web freelance',
            'email' => 'cedric.badjah@gmail.com',
            'address' => 'Montpellier',
            'url' => 'https://samakunchan-technology.com/'
        ]);
    }
}
