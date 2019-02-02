<?php

namespace App\Repository;

use App\Entity\BookingRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BookingRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingRequest[]    findAll()
 * @method BookingRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BookingRequest::class);
    }

    // /**
    //  * @return BookingRequest[] Returns an array of BookingRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BookingRequest
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
