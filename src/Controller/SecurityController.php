<?php

namespace App\Controller;

use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * Get app token for sign in and query API
     *
     * @Route("/login_check", name="login", methods={"POST"})
     *
     * @SWG\Tag(name="Authentication")
     *
     * @SWG\Parameter(
     *   name="authToken",
     *   description="Fields typing data to get an AuthToken",
     *   in="body",
     *   required=true,
     *   type="string",
     *   @SWG\Schema(
     *     type="object",
     *     title="Authentication field",
     *     @SWG\Property(property="username", type="string", example="sam@test.fr"),
     *     @SWG\Property(property="password", type="string", example="123456")
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *     @SWG\Schema(
     *      type="string",
     *      title="Token",
     *      @SWG\Property(property="token", type="string"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad request - Invalid JSON",
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials"
     * )
     *
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SecurityController.php',
        ]);
    }
}
