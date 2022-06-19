<?php

namespace App\Controller\Api\Task\GetTask\v1;

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
     * @Route("/api/v1/get-task", methods={"GET"})
     *
     */
    public function getTaskAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $tasks = $this->taskService->getTasks($page ?? 0, $perPage ?? 20);
        $code = empty($tasks) ? 204 : 200;

        return new JsonResponse(['tasks' => array_map(static fn(Task $task) => $task->toArray(), $tasks)], $code);
    }
}