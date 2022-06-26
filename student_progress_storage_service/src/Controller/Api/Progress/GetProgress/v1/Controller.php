<?php

namespace App\Controller\Api\Progress\GetProgress\v1;

use App\Entity\Progress;
use App\Service\ProgressService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * @Route("/api/v1/get-progress", methods={"GET"})
     *
     */
    public function getProgressAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $progress = $this->progressService->getProgress($page ?? 0, $perPage ?? 20);
        $code = empty($progress) ? 204 : 200;

        return new JsonResponse(['progresss' => array_map(static fn(Progress $progress) => $progress->toArray(), $progress)], $code);
    }

    /**
     * @Route("/api/v1/get-progress-by-lesson", methods={"GET"})
     *
     */
    public function getUserProgressByLessonAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $lessonId = $request->query->get('lessonId');
        $progress = $this->progressService->getProgressByLesson($userId, $lessonId);
        $code = empty($progress) ? 204 : 200;

        return new JsonResponse(['lessonId' => $lessonId, 'userId' => $userId, 'progress' => $progress], $code);
    }

    /**
     * @Route("/api/v1/get-progress-by-period", methods={"GET"})
     *
     */
    public function getUserProgressByPeriodAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $fromDate = $request->query->get('fromDate');
        $toDate = $request->query->get('toDate');
        $progress = $this->progressService->getProgressByPeriod($userId, $fromDate, $toDate);
        $code = empty($progress) ? 204 : 200;

        return new JsonResponse(['userId' => $userId, 'fromDate' => $fromDate, 'toDate' => $toDate, 'progress' => $progress], $code);
    }

    /**
     * @Route("/api/v1/get-progress-by-skill", methods={"GET"})
     *
     */
    public function getUserProgressBySkillAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $skillId = $request->query->get('skillId');
        $progress = $this->progressService->getProgressByUnit($userId, $skillId);
        $code = empty($progress) ? 204 : 200;

        return new JsonResponse(['userId' => $userId, 'skillId' => $skillId, 'progress' => array_map(static fn(Progress $progress) => $progress->toArray(), $progress)], $code);
    }

    /**
     * @Route("/api/v1/get-progress-by-course", methods={"GET"})
     *
     */
    public function getUserProgressByCourseAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $courseId = $request->query->get('courseId');
        $progress = $this->progressService->getProgressByCourse($userId, $courseId);
        $code = empty($progress) ? 204 : 200;

        return new JsonResponse(['userId' => $userId, 'skillId' => $courseId, 'progress' => $progress], $code);
    }
}