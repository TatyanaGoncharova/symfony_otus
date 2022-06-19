<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    /**
     * @return Task[]
     */
    public function getTask(int $page, int $perPage): array
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