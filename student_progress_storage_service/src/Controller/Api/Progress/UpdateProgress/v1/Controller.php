<?php

namespace App\Controller\Api\Progress\UpdateProgress\v1;

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
     * @Route("/api/v1/update-progress", methods={"PATCH"})
     *
     */
    public function updateProgressAction(Request $request): Response
    {
        $progressId = $request->request->get('progressId');
        $progressRate = $request->request->get('progressRate');
        $result = $this->progressService->updateProgressRate($progressId, $progressRate);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}