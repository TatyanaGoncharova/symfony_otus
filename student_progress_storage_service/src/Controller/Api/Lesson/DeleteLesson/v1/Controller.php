<?php

namespace App\Controller\Api\Lesson\DeleteLesson\v1;

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
     * @Route("/api/v1/delete-lesson", methods={"DELETE"})
     *
     */
    public function deleteLessonAction(Request $request): Response
    {
        $lessonId = $request->query->get('lessonId');
        $result = $this->lessonService->deleteLesson($lessonId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}