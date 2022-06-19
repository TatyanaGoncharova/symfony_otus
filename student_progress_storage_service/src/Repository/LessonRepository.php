<?php

namespace App\Repository;

use App\Entity\Lesson;
use Doctrine\ORM\EntityRepository;

class LessonRepository extends EntityRepository
{
    /**
     * @return Lesson[]
     */
    public function getLessons(int $page, int $perPage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }
}