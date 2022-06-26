<?php

namespace App\Service;

use App\DTO\ProgressOutputDTO;
use App\Entity\Course;
use App\Entity\Skill;
use App\Entity\Task;
use App\Entity\Progress;
use App\Entity\User;
use App\Repository\ProgressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProgressService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var TagAwareCacheInterface */
    private TagAwareCacheInterface $cache;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, TagAwareCacheInterface $cache,)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
        $this->repository = $this->entityManager->getRepository(Progress::class);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function saveProgress(int $userId, int $taskId, int $rate): bool
    {
        $progress = new Progress();
        $student = $this->getUserById($userId);
        $task = $this->getTaskById($taskId);

        if (empty($task) || empty($student)) {
            return false;
        }
        $progress->setTask($task);
        $progress->setStudent($student);
        $progress->setRate($rate);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();
        $this->cache->invalidateTags(["progress_userId_{$userId}"]);
        return true;
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

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function updateProgressRate(int $progressId, string $progressRate): bool
    {

        /** @var Progress $progress */
        $progress = $this->repository->find($progressId);
        if ($progress === null) {
            return false;
        }
        $progress->setRate($progressRate);
        $this->entityManager->flush();
        $this->cache->invalidateTags(["progress_userId_{$progress->getId()}"]);
        return true;
    }

    public function deleteProgress(int $progressId): bool
    {
        /** @var Progress $progress */
        $progress = $this->repository->find($progressId);
        if ($progress === null) {
            return false;
        }
        $this->entityManager->remove($progress);
        $this->entityManager->flush();
        $this->cache->invalidateTags(["progress_userId_{$progress->getId()}"]);
        return true;
    }

    /**
     * @return Progress[]
     */
    public function getProgress(int $page, int $perPage): array
    {
        return $this->repository->getProgresss($page, $perPage);
    }

    /**
     * @param int|null $userId
     * @param int|null $lessonId
     * @return array|null
     */
    public function getProgressByLesson(?int $userId, ?int $lessonId): ?array
    {
        if (empty($userId) || empty($lessonId)) {
            return null;
        }
        return $this->cache->get("progress_by_lesson_userId_{$userId}_lessonId_{$lessonId}",
            function (ItemInterface $item) use ($userId, $lessonId) {
                $progress =  $this->repository->getProgressByLesson($userId, $lessonId);
                $item->set($progress);
                $item->tag("progress_userId_{$userId}");
                return $progress;
            });
    }

    /**
     * @param int $userId
     * @param string $fromDate
     * @param string $toDate
     * @return array|null
     */
    public function getProgressByPeriod(int $userId, string $fromDate, string $toDate): ?array
    {
        if (empty($userId) || empty($fromDate) || empty($toDate)) {
            return null;
        }

        return $this->cache->get("progress_by_period_userId_{$userId}_fromDate_{$fromDate}_toDate_{$toDate}",
            function (ItemInterface $item) use ($userId, $fromDate, $toDate) {
                $result = [];
                $resultData = $this->repository->getProcessesByPeriod($userId, $fromDate, $toDate);
                foreach ($resultData as $data) {
                    $progressDTO = new ProgressOutputDTO($data);
                    $course = $progressDTO->task->getLesson()->getCourse()->getTitle();
                    $lesson = $progressDTO->task->getLesson()->getTitle();;
                    if(!isset($result[$course]['rates'])){
                        $result[$course]['rates'] = 0;
                    }
                    if(!isset($result[$course]['lessons']) || !in_array($lesson, $result[$course]['lessons'])){
                        $result[$course]['lessons'][] = $lesson;
                    }
                    $result[$course]['tasks'][] = $progressDTO->task->getTitle();
                    $result[$course]['rates'] += $progressDTO->rate;
                }
                $item->set($result);
                $item->tag("progress_userId_{$userId}");
                return $result;
            });
    }

    public function getProgressByUnit(?int $userId, ?int $unitId): ?array
    {
        if (empty($userId) || empty($unitId)) {
            return null;
        }

        return $this->cache->get("points_by_unit_userId_{$userId}_unitId_{$unitId}",
            function (ItemInterface $item) use ($userId, $unitId) {
                $progress = $this->repository->getProcessesByUnit($userId, $unitId);
                $item->set($progress);
                $item->tag("progress_userId_{$userId}");
                return $progress;
            });

    }

    public function getProgressByCourse(?int $userId, ?int $courseId): ?array
    {
        if (empty($userId) || empty($courseId)) {
            return null;
        }
        return $this->cache->get("points_by_course_userId_{$userId}_courseId_{$courseId}",
            function (ItemInterface $item) use ($userId, $courseId) {
                $result = [];
                $resultData = $this->repository->getProcessesByCourse($userId, $courseId);
                foreach ($resultData as $data) {
                    $progressDTO = new ProgressOutputDTO($data);
                    $result['course'] = $progressDTO->task->getLesson()->getCourse()->getTitle();
                    $lesson = $progressDTO->task->getLesson()->getTitle();;
                    if(!isset($result['rates'])){
                        $result['rates'] = 0;
                    }
                    if(!isset($result['lessons']) || !in_array($lesson, $result['lessons'])){
                        $result['lessons'][] = $lesson;
                    }
                    $result['tasks'][] = $progressDTO->task->getTitle();
                    $result['rates'] += $progressDTO->rate;
                }
                $item->set($result);
                $item->tag("progress_userId_{$userId}");
                return $result;
            });
    }
}