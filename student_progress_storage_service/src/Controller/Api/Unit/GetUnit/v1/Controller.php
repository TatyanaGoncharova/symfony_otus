<?php

namespace App\Controller\Api\Unit\GetUnit\v1;

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
     * @Route("/api/v1/get-unit", methods={"GET"})
     *
     */
    public function getUnitAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $units = $this->unitService->getUnits($page ?? 0, $perPage ?? 20);
        $code = empty($units) ? 204 : 200;

        return new JsonResponse(['units' => array_map(static fn(Unit $unit) => $unit->toArray(), $units)], $code);
    }
}