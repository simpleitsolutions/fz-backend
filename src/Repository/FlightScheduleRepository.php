<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FlightScheduleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FlightScheduleRepository extends EntityRepository
{
    public function getFlightScheduleForDate(\DateTime $givenDate)
    {
        return $this->createQueryBuilder('fs')
            ->where('fs.affectiveStartDate <= :currentDate')
            ->andWhere('fs.affectiveEndDate >= :currentDate')
            ->setParameter('currentDate', $givenDate->format('Y-m-d'))
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
