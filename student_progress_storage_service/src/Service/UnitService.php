<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Skill;
use App\Entity\Task;
use App\Entity\Unit;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;

class UnitService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveUnit(int $taskId, int $skillId, int $percent): ?int
    {
        $unit = new Unit();
        $task = $this->getTaskById($taskId);
        $skill = $this->getSkillById($skillId);
        if(empty($task) || empty($skill)){
            return null;
        }
        $unit->setTask($task);
        $unit->setSkill($skill);
        $unit->setPercent($percent);
        $this->entityManager->persist($unit);
        $this->entityManager->flush();

        return $unit->getId();
    }

    private function getTaskById(int $taskId)
    {
        $taskRepository = $this->entityManager->getRepository(Task::class);
        return $taskRepository->find($taskId);
    }

    private function getSkillById(int $skillId)
    {
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        return $skillRepository->find($skillId);
    }

    public function updateUnitPercent(int $unitId, string $unitPercent): bool
    {
        /** @var UnitRepository $unitRepository */
        $unitRepository = $this->entityManager->getRepository(Unit::class);
        /** @var Unit $unit */
        $unit = $unitRepository->find($unitId);
        if ($unit === null) {
            return false;
        }
        $unit->setPercent($unitPercent);
        $this->entityManager->flush();

        return true;
    }

    public function deleteUnit(int $unitId): bool
    {
        /** @var UnitRepository $unitRepository */
        $unitRepository = $this->entityManager->getRepository(Unit::class);
        /** @var Unit $unit */
        $unit = $unitRepository->find($unitId);
        if ($unit === null) {
            return false;
        }
        $this->entityManager->remove($unit);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return Unit[]
     */
    public function getUnits(int $page, int $perPage): array
    {
        /** @var UnitRepository $unitRepository */
        $unitRepository = $this->entityManager->getRepository(Unit::class);

        return $unitRepository->getUnits($page, $perPage);
    }
}