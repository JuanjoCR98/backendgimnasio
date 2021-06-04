<?php

namespace App\Repository;

use App\Entity\Estadistica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Estadistica|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estadistica|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estadistica[]    findAll()
 * @method Estadistica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadisticaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estadistica::class);
    }

    // /**
    //  * @return Estadistica[] Returns an array of Estadistica objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Estadistica
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
