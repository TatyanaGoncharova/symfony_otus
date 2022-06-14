<?php

namespace App\Controller\Api\Course\UpdateCourse\v1;

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
     * @Route("/api/v1/update-course", methods={"PATCH"})
     *
     */
    public function updateCourseAction(Request $request): Response
    {
        $courseId = $request->request->get('courseId');
        $courseTitle = $request->request->get('courseTitle');
        $result = $this->courseService->updateCourse($courseId, $courseTitle);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}