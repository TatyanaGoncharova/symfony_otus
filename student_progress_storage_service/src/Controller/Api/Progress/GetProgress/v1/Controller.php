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
}