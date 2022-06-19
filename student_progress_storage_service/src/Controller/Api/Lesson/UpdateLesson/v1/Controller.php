<?php

namespace App\Controller\Api\Lesson\UpdateLesson\v1;

use App\Entity\Lesson;
use App\Service\LessonService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private LessonService $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    /**
     * @Route("/api/v1/update-lesson", methods={"PATCH"})
     *
     */
    public function updateLessonAction(Request $request): Response
    {
        $lessonId = $request->request->get('lessonId');
        $lessonTitle = $request->request->get('lessonTitle');
        $result = $this->lessonService->updateLessonTitle($lessonId, $lessonTitle);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}