<?php
namespace App\Repository;

use App\Entity\Pilot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BookingRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingRequest[]    findAll()
 * @method BookingRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PilotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pilot::class);
    }

    public function getFlyZermattPilots()
    {
        return $this->createQueryBuilder('p')
        ->where('p.flyZermattPilot = :flyZermattPilot')
        ->setParameter('flyZermattPilot', true)
        ->getQuery()
        ->execute();
    }
}
