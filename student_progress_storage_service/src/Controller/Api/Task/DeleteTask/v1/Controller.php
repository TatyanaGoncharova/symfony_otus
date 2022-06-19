<?php

namespace App\Controller\Api\Task\DeleteTask\v1;

use App\Entity\Task;
use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @Route("/api/v1/delete-task", methods={"DELETE"})
     *
     */
    public function deleteTaskAction(Request $request): Response
    {
        $taskId = $request->query->get('taskId');
        $result = $this->taskService->deleteTask($taskId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}