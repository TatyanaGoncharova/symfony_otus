<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;

class LessonService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveLesson(string $title, int $courseId): ?int
    {
        $lesson = new Lesson();
        $course = $this->getCourseById($courseId);
        $lesson->setTitle($title);
        $lesson->setCourse($course);
        $this->entityManager->persist($lesson);
        $this->entityManager->flush();

        return $lesson->getId();
    }

    private function getCourseById(int $courseId)
    {
        $courseRepository = $this->entityManager->getRepository(Course::class);
         return $courseRepository->find($courseId);
    }

    public function updateLessonTitle(int $lessonId, string $lessonTitle): bool
    {
        /** @var LessonRepository $lessonRepository */
        $lessonRepository = $this->entityManager->getRepository(Lesson::class);
        /** @var Lesson $lesson */
        $lesson = $lessonRepository->find($lessonId);
        if ($lesson === null) {
            return false;
        }
        $lesson->setTitle($lessonTitle);
        $this->entityManager->flush();

        return true;
    }

    public function updateLessonCourse(int $lessonId, Course $course): bool
    {
        /** @var LessonRepository $lessonRepository */
        $lessonRepository = $this->entityManager->getRepository(Lesson::class);
        /** @var Lesson $lesson */
        $lesson = $lessonRepository->find($lessonId);
        if ($lesson === null) {
            return false;
        }
        $lesson->setCourse($course);
        $this->entityManager->flush();

        return true;
    }

    public function deleteLesson(int $lessonId): bool
    {
        /** @var LessonRepository $lessonRepository */
        $lessonRepository = $this->entityManager->getRepository(Lesson::class);
        /** @var Lesson $lesson */
        $lesson = $lessonRepository->find($lessonId);
        if ($lesson === null) {
            return false;
        }
        $this->entityManager->remove($lesson);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return Lesson[]
     */
    public function getLessons(int $page, int $perPage): array
    {
        /** @var LessonRepository $lessonRepository */
        $lessonRepository = $this->entityManager->getRepository(Lesson::class);

        return $lessonRepository->getLessons($page, $perPage);
    }
}