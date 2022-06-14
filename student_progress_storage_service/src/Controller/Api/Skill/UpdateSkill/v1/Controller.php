<?php

namespace App\Controller\Api\Skill\UpdateSkill\v1;

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
     * @Route("/api/v1/update-skill", methods={"PATCH"})
     *
     */
    public function updateSkillAction(Request $request): Response
    {
        $skillId = $request->request->get('skillId');
        $skillName = $request->request->get('skillName');
        $result = $this->skillService->updateSkill($skillId, $skillName);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}