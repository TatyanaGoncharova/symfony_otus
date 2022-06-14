<?php

namespace App\Controller\Api\User\DeleteUser\v1;

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
     * @Route("/api/v1/delete-user", methods={"DELETE"})
     *
     */
    public function deleteUserAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $result = $this->userService->deleteUser($userId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}