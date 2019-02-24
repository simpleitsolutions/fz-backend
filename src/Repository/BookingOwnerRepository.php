<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BookingOwnerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookingOwnerRepository extends EntityRepository
{

    public function getDefaultOwner()
    {
        return $this->createQueryBuilder('bo')
        ->where('bo.id = 9')
        ->getQuery()
        ->setMaxResults(1)
        ->execute();
    }
}