<?php

namespace App\Controller\Api\Skill\GetSkills\v1;

use App\Entity\Skill;
use App\Service\SkillService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private SkillService $userService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * @Route("/api/v1/get-skill", methods={"GET"})
     *
     */
    public function getSkillAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $skills = $this->skillService->getSkills($page ?? 0, $perPage ?? 20);
        $code = empty($skills) ? 204 : 200;

        return new JsonResponse(['skills' => array_map(static fn(Skill $skill) => $skill->toArray(), $skills)], $code);
    }
}