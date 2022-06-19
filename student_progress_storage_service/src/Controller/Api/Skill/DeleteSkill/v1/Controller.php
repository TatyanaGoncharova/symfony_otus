<?php

namespace App\Controller\Api\Skill\DeleteSkill\v1;

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
     * @Route("/api/v1/delete-skill", methods={"DELETE"})
     *
     */
    public function deleteSkillAction(Request $request): Response
    {
        $skillId = $request->query->get('skillId');
        $result = $this->skillService->deleteSkill($skillId);

        return new JsonResponse(['success' => $result], $result ? 200 : 404);
    }
}