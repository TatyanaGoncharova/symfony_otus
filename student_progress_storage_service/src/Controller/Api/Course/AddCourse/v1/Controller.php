<?php

namespace App\Controller\Api\Course\AddCourse\v1;

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
     * @Route("/api/v1/add-course", methods={"POST"})
     *
     */
    public function addCourseAction(Request $request): Response
    {
        $courseTitle = $request->request->get('title');
        $courseId = $this->courseService->saveCourse($courseTitle);
        [$data, $code] = $courseId === null ?
            [['success' => false], 400] :
            [['success' => true, 'courseId' => $courseId], 200];

        return new JsonResponse($data, $code);
    }
}