<?php

namespace App\Controller\Api\Lesson\AddLesson\v1;

use App\Entity\Lesson;
use App\Service\CourseService;
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
     * @Route("/api/v1/add-lesson", methods={"GET"})
     *
     */
    public function addLessonAction(Request $request): Response
    {
        $lessonTitle = $request->query->get('title');
        $courseId = $request->query->get('courseId');
        $lessonId = $this->lessonService->saveLesson($lessonTitle, $courseId);
        [$data, $code] = empty($lessonId) ?
            [['success' => false], 400] :
            [['success' => true, 'lessonId' => $lessonId], 200];

        return new JsonResponse($data, $code);
    }
}