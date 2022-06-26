<?php

namespace App\Service;

use App\Entity\Lesson;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveTask(string $title, int $lessonId): bool
    {
        if(empty($title)){
            return false;
        }
        $task = new Task();
        $task->setTitle($title);
        $lessonRepository = $this->entityManager->getRepository(Lesson::class);
        $lesson = $lessonRepository->find($lessonId);
        $task->setLesson($lesson);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return true;
    }

    public function updateTaskTitle(int $taskId, string $taskTitle): bool
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->entityManager->getRepository(Task::class);
        /** @var Task $task */
        $task = $taskRepository->find($taskId);
        if ($task === null) {
            return false;
        }
        $task->setTitle($taskTitle);
        $this->entityManager->flush();

        return true;
    }

    public function deleteTask(int $taskId): bool
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->entityManager->getRepository(Task::class);
        /** @var Task $task */
        $task = $taskRepository->find($taskId);
        if ($task === null) {
            return false;
        }
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return Task[]
     */
    public function getTasks(int $page, int $perPage): array
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = $this->entityManager->getRepository(Task::class);

        return $taskRepository->getTasks($page, $perPage);
    }
}