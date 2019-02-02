<?php

namespace App\Repository;

use App\Entity\BookingRequestGroupCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BookingRequestGroupCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingRequestGroupCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingRequestGroupCondition[]    findAll()
 * @method BookingRequestGroupCondition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRequestGroupConditionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BookingRequestGroupCondition::class);
    }

    // /**
    //  * @return BookingRequestGroupCondition[] Returns an array of BookingRequestGroupCondition objects
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
    public function findOneBySomeField($value): ?BookingRequestGroupCondition
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
