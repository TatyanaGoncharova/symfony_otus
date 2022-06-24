<?php

namespace App\Controller\Api\Progress\AddProgress\v1;

use App\DTO\ProgressAMQPDTO;
use App\Entity\Progress;
use App\Service\AsyncService;
use App\Service\ProgressService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private ProgressService $progressService;
    private $asyncService;

    public function __construct(ProgressService $progressService, AsyncService  $asyncService)
    {
        $this->progressService = $progressService;
        $this->asyncService = $asyncService;
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
        $message = (new ProgressAMQPDTO($userId, $taskId, $rate))->toAMQPMessage();
        $result = $this->asyncService->publishToExchange(AsyncService::ADD_PROGRESS, $message);
        [$data, $code] = empty($result) ?
            [['success' => false], 400] :
            [['success' => true, 'progressId' => $result], 200];

        return new JsonResponse($data, $code);
    }
}