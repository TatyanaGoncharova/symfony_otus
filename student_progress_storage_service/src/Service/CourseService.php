<?php

namespace App\Service;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;

class CourseService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveCourse(string $title): ?int
    {
        $course = new Course();
        $course->setTitle($title);
        $this->entityManager->persist($course);
        $this->entityManager->flush();

        return $course->getId();
    }

    public function updateCourse(int $courseId, string $courseTitle): bool
    {
        /** @var CourseRepository $courseRepository */
        $courseRepository = $this->entityManager->getRepository(Course::class);
        /** @var Course $course */
        $course = $courseRepository->find($courseId);
        if ($course === null) {
            return false;
        }
        $course->setTitle($courseTitle);
        $this->entityManager->flush();

        return true;
    }

    public function deleteCourse(int $courseId): bool
    {
        /** @var CourseRepository $courseRepository */
        $courseRepository = $this->entityManager->getRepository(Course::class);
        /** @var Course $course */
        $course = $courseRepository->find($courseId);
        if ($course === null) {
            return false;
        }
        $this->entityManager->remove($course);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return Course[]
     */
    public function getCourses(int $page, int $perPage): array
    {
        /** @var CourseRepository $courseRepository */
        $courseRepository = $this->entityManager->getRepository(Course::class);

        return $courseRepository->getCourses($page, $perPage);
    }

    /**
     * @param int $id
     * @return Course|null
     */
    public function getById(int $id): ?Course
    {
        /** @var CourseRepository $courseRepository */
        $courseRepository = $this->entityManager->getRepository(Course::class);

        return $courseRepository->find($id);
    }
}