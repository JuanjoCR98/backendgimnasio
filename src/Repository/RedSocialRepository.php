<?php

namespace App\Repository;

use App\Entity\RedSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RedSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method RedSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method RedSocial[]    findAll()
 * @method RedSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedSocialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedSocial::class);
    }

    // /**
    //  * @return RedSocial[] Returns an array of RedSocial objects
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
    public function findOneBySomeField($value): ?RedSocial
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
