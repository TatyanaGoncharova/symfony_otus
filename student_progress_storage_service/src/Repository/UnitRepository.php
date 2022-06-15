<?php

namespace App\Repository;

use App\Entity\Unit;
use Doctrine\ORM\EntityRepository;

class UnitRepository extends EntityRepository
{
    /**
     * @return Unit[]
     */
    public function getUnits(int $page, int $perPage): array
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