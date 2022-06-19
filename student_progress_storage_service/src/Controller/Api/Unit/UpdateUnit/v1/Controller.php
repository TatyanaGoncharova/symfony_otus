<?php

namespace App\Controller\Api\Unit\UpdateUnit\v1;

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
     * @Route("/api/v1/update-unit", methods={"PATCH"})
     *
     */
    public function updateUnitAction(Request $request): Response
    {
        $unitId = $request->request->get('unitId');
        $unitPercent = $request->request->get('unitPercent');
        $result = $this->unitService->updateUnitPercent($unitId, $unitPercent);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}