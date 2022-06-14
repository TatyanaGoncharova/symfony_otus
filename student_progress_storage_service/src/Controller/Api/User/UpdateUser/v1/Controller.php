<?php

namespace App\Controller\Api\User\UpdateUser\v1;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/api/v1/update-user", methods={"PATCH"})
     *
     */
    public function updateUserAction(Request $request): Response
    {
        $userId = $request->request->get('userId');
        $login = $request->request->get('login');
        $result = $this->userService->updateUser($userId, $login);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}