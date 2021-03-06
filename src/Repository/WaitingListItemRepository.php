<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * WaitingListItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WaitingListItemRepository extends EntityRepository
{
    public function getWaitingListForDate(\DateTime $givenDate)
    {
        return $this->createQueryBuilder('wl')
		    ->where('wl.waitingListItemDate = :startdate')
		    ->setParameter('startdate', $givenDate->format('Y-m-d'))
		    ->getQuery()
			->execute();
    }
}
