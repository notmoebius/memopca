<?php

namespace App\Repository;

use App\Entity\CrisisRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CrisisRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrisisRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrisisRoom[]    findAll()
 * @method CrisisRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrisisRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrisisRoom::class);
    }

    // /**
    //  * @return CrisisRoom[] Returns an array of CrisisRoom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CrisisRoom
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
