<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Skill;
use App\Entity\Task;
use App\Entity\Progress;
use App\Entity\User;
use App\Repository\ProgressRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProgressService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveProgress(int $userId, int $taskId,  int $rate): ?int
    {
        $progress = new Progress();
        $student = $this->getUserById($userId);
        $task = $this->getTaskById($taskId);

        if(empty($task) || empty($student)){
            return null;
        }
        $progress->setTask($task);
        $progress->setStudent($student);
        $progress->setRate($rate);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        return $progress->getId();
    }

    private function getTaskById(int $taskId)
    {
        $taskRepository = $this->entityManager->getRepository(Task::class);
        return $taskRepository->find($taskId);
    }

    private function getUserById(int $userId)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        return $userRepository->find($userId);
    }

    public function updateProgressRate(int $progressId, string $progressRate): bool
    {
        /** @var ProgressRepository $progressRepository */
        $progressRepository = $this->entityManager->getRepository(Progress::class);
        /** @var Progress $progress */
        $progress = $progressRepository->find($progressId);
        if ($progress === null) {
            return false;
        }
        $progress->setRate($progressRate);
        $this->entityManager->flush();

        return true;
    }

    public function deleteProgress(int $progressId): bool
    {
        /** @var ProgressRepository $progressRepository */
        $progressRepository = $this->entityManager->getRepository(Progress::class);
        /** @var Progress $progress */
        $progress = $progressRepository->find($progressId);
        if ($progress === null) {
            return false;
        }
        $this->entityManager->remove($progress);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return Progress[]
     */
    public function getProgress(int $page, int $perPage): array
    {
        /** @var ProgressRepository $progressRepository */
        $progressRepository = $this->entityManager->getRepository(Progress::class);

        return $progressRepository->getProgresss($page, $perPage);
    }
}