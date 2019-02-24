<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AvailabilityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AvailabilityRepository extends EntityRepository
{
    public function getAvailabilityForPilotOnDate(Pilot $pilot, \DateTime $scheduleDate)
    {
        return $this->createQueryBuilder('a')
            ->where('a.pilot = :pilot')
            ->andWhere('a.unavailableFlightDate = :unavailableFlightDate')
            ->setParameter('pilot', $pilot )
            ->setParameter('unavailableFlightDate', $scheduleDate->format("Y-m-d") )
            ->orderBy('a.unavailableFlightDate', 'ASC')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
    
}