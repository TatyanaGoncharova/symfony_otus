<?php

namespace App\Controller\Api\Lesson\GetLesson\v1;

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
     * @Route("/api/v1/get-lesson", methods={"GET"})
     *
     */
    public function getLessonAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $lessons = $this->lessonService->getLessons($page ?? 0, $perPage ?? 20);
        $code = empty($lessons) ? 204 : 200;

        return new JsonResponse(['lessons' => array_map(static fn(Lesson $lesson) => $lesson->toArray(), $lessons)], $code);
    }
}