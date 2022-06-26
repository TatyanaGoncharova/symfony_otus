<?php

namespace App\Controller\Api\Progress\UpdateProgress\v1;

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
     * @Route("/api/v1/update-progress", methods={"PATCH"})
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function updateProgressAction(Request $request): Response
    {
        $progressId = $request->request->get('progressId');
        $progressRate = $request->request->get('progressRate');
        try {
            $result = $this->progressService->updateProgressRate($progressId, $progressRate);
            $this->logger->info("Update progress with id " . $progressId);
        } catch (\Throwable $e){
            $this->logger->error("Can't update progress with id " . $progressId . $e ->getMessage());
        }

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}