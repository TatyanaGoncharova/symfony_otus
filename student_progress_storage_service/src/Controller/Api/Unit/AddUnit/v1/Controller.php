<?php

namespace App\Controller\Api\Unit\AddUnit\v1;

use App\Entity\Unit;
use App\Service\UnitService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    /**
     * @Route("/api/v1/add-unit", methods={"GET"})
     *
     */
    public function addUnitAction(Request $request): Response
    {
        $taskId = $request->query->get('taskId');
        $skillId = $request->query->get('skillId');
        $percent = $request->query->get('percent');
        $unitId = $this->unitService->saveUnit($taskId, $skillId, $percent);
        [$data, $code] = empty($unitId) ?
            [['success' => false], 400] :
            [['success' => true, 'unitId' => $unitId], 200];

        return new JsonResponse($data, $code);
    }
}