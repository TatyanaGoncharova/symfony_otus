<?php

namespace App\Controller\Api\Course\DeleteCourse\v1;

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
     * @Route("/api/v1/delete-course", methods={"DELETE"})
     *
     */
    public function deleteCourseAction(Request $request): Response
    {
        $courseId = $request->query->get('courseId');
        $result = $this->courseService->deleteCourse($courseId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}