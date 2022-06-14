<?php

namespace App\Controller\Api\Task\UpdateTask\v1;

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
     * @Route("/api/v1/update-task", methods={"PATCH"})
     *
     */
    public function updateTaskAction(Request $request): Response
    {
        $taskId = $request->request->get('taskId');
        $taskTitle = $request->request->get('taskTitle');
        $result = $this->taskService->updateTaskTitle($taskId, $taskTitle);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}