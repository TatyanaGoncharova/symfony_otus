<?php

namespace App\Controller\Api\Progress\DeleteProgress\v1;

use App\Entity\Progress;
use App\Service\ProgressService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private ProgressService $progressService;
    private LoggerInterface $logger;

    public function __construct(ProgressService $progressService, LoggerInterface $logger)
    {
        $this->progressService = $progressService;
        $this->logger = $logger;
    }

    /**
     * @Route("/api/v1/delete-progress", methods={"DELETE"})
     *
     */
    public function deleteProgressAction(Request $request): Response
    {
        $progressId = $request->query->get('progressId');
        $result = $this->progressService->deleteProgress($progressId);
        $this->logger->info('Delete progress with id ' . $progressId);
        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}