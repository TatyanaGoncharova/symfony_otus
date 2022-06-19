<?php

namespace App\Service;

use App\Entity\Skill;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class SkillService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveSkill(string $name): ?int
    {
        $skill = new Skill();
        $skill->setSkillName($name);
        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        return $skill->getId();
    }

    public function updateSkill(int $skillId, string $skillName): bool
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        /** @var Skill $skill */
        $skill = $skillRepository->find($skillId);
        if ($skill === null) {
            return false;
        }
        $skill->setSkillName($skillName);
        $this->entityManager->flush();

        return true;
    }

    public function deleteSkill(int $skillId): bool
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        /** @var Skill $skill */
        $skill = $skillRepository->find($skillId);
        if ($skill === null) {
            return false;
        }
        $this->entityManager->remove($skill);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return Skill[]
     */
    public function getSkills(int $page, int $perPage): array
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->entityManager->getRepository(Skill::class);

        return $skillRepository->getSkills($page, $perPage);
    }
}