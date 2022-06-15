<?php

namespace App\Controller\Api\Progress\AddProgress\v1;

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
     * @Route("/api/v1/add-progress", methods={"GET"})
     *
     */
    public function addProgressAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $taskId = $request->query->get('taskId');
        $rate = $request->query->get('rate');
        $progressId = $this->progressService->saveProgress($userId, $taskId, $rate);
        [$data, $code] = empty($progressId) ?
            [['success' => false], 400] :
            [['success' => true, 'progressId' => $progressId], 200];

        return new JsonResponse($data, $code);
    }
}