<?php

namespace App\Controller\Api\Unit\DeleteUnit\v1;

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
     * @Route("/api/v1/delete-unit", methods={"DELETE"})
     *
     */
    public function deleteUnitAction(Request $request): Response
    {
        $unitId = $request->query->get('unitId');
        $result = $this->unitService->deleteUnit($unitId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}