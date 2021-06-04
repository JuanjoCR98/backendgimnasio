<?php

namespace App\Repository;

use App\Entity\Rutina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rutina|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rutina|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rutina[]    findAll()
 * @method Rutina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RutinaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rutina::class);
    }

    // /**
    //  * @return Rutina[] Returns an array of Rutina objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rutina
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
