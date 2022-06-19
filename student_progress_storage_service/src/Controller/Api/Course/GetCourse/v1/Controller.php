<?php

namespace App\Controller\Api\Course\GetCourse\v1;

use App\Entity\Course;
use App\Service\CourseService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * @Route("/api/v1/get-course", methods={"GET"})
     *
     */
    public function getCourseAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $courses = $this->courseService->getCourses($page ?? 0, $perPage ?? 20);
        $code = empty($courses) ? 204 : 200;

        return new JsonResponse(['courses' => array_map(static fn(Course $course) => $course->toArray(), $courses)], $code);
    }
}