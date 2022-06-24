<?php

namespace App\Repository;

use App\Entity\Lesson;
use App\Entity\Progress;
use App\Entity\Unit;
use Container1ibynMr\getDebug_ArgumentResolver_RequestAttributeService;
use Doctrine\ORM\EntityRepository;

class ProgressRepository extends EntityRepository
{
    /**
     * @return Progress[]
     */
    public function getProgresss(int $page, int $perPage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    public function getProgressByLesson(?int $userId, ?int $lessonId): ?array
    {

        return $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(p.rate) AS total')
            ->from($this->getClassName(), 'p')
            ->join('p.task', 't')
            ->where('t.lesson = :lesson')
            ->andWhere('p.student = :user')
            ->setParameter('user', $userId)
            ->setParameter('lesson', $lessonId)
            ->getQuery()
            ->getResult();

    }

    public function getProcessesByUnit(int $userId, string $skillId): ?array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select("SUM(p.rate) AS total")
            ->from($this->getClassName(), 'p')
            ->join(Unit::class, 'u', 'WITH', 'u.task = p.task')
            ->andWhere('p.student = :user')
            ->andWhere('u.skill = :skillId')
            ->groupBy("u.skill")
            ->setParameter('user', $userId)
            ->setParameter('skillId', $skillId);
        return $qb->getQuery()->execute();
    }

    public function getProcessesByPeriod(int $userId, string $fromDate, string $toDate): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from($this->getClassName(), 'p')
            ->where('p.student = :user')
            ->andWhere('p.createdAt >= :fromDate')
            ->andWhere('p.createdAt <= :toDate')
            ->setParameter('user', $userId)
            ->setParameter('fromDate', new \DateTime($fromDate))
            ->setParameter('toDate', new \DateTime($toDate));

        return $qb->getQuery()->getResult();
    }

    public function getProcessesByCourse(int $userId, int $courseId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from($this->getClassName(), 'p')
            ->join('p.task', 't')
            ->join('t.lesson', 'l')
            ->where('p.student = :user')
            ->andWhere('l.course = :courseId')
            ->setParameter('user', $userId)
            ->setParameter('courseId', $courseId);

        return $qb->getQuery()->getResult();
    }

}