<?php

namespace App\Controller\Api\Task\AddTask\v1;

use App\Entity\Task;
use App\Service\LessonService;
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
     * @Route("/api/v1/add-task", methods={"GET"})
     *
     */
    public function addTaskAction(Request $request): Response
    {
        $taskTitle = $request->query->get('title');
        $lessonId = $request->query->get('lessonId');
        $taskId = $this->taskService->saveTask($taskTitle, $lessonId);
        [$data, $code] = $taskId === null ?
            [['success' => false], 400] :
            [['success' => true, 'taskId' => $taskId], 200];

        return new JsonResponse($data, $code);
    }
}