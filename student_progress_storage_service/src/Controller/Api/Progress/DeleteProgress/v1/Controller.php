<?php

namespace App\Controller\Api\Progress\DeleteProgress\v1;

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
     * @Route("/api/v1/delete-progress", methods={"DELETE"})
     *
     */
    public function deleteProgressAction(Request $request): Response
    {
        $progressId = $request->query->get('progressId');
        $result = $this->progressService->deleteProgress($progressId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}