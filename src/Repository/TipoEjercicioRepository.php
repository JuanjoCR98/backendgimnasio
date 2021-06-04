<?php

namespace App\Repository;

use App\Entity\TipoEjercicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoEjercicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoEjercicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoEjercicio[]    findAll()
 * @method TipoEjercicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoEjercicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoEjercicio::class);
    }

    // /**
    //  * @return TipoEjercicio[] Returns an array of TipoEjercicio objects
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
    public function findOneBySomeField($value): ?TipoEjercicio
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
