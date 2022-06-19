<?php

namespace App\Controller\Api\Skill\AddSkill\v1;

use App\Entity\Skill;
use App\Service\SkillService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{

    private SkillService $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * @Route("/api/v1/add-skill", methods={"POST"})
     *
     */
    public function addSkillAction(Request $request): Response
    {
        $skillName = $request->request->get('name');
        $skillId = $this->skillService->saveSkill($skillName);
        [$data, $code] = $skillId === null ?
            [['success' => false], 400] :
            [['success' => true, 'skillId' => $skillId], 200];

        return new JsonResponse($data, $code);
    }
}