<?php

namespace App\Repository;

use App\Entity\TypeCrisisRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeCrisisRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCrisisRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCrisisRoom[]    findAll()
 * @method TypeCrisisRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCrisisRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCrisisRoom::class);
    }

    // /**
    //  * @return TypeCrisisRoom[] Returns an array of TypeCrisisRoom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeCrisisRoom
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
