<?php

namespace App\Repository;

use App\Entity\BuildRoute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BuildRoute|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuildRoute|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuildRoute[]    findAll()
 * @method BuildRoute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildRouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuildRoute::class);
    }

    // /**
    //  * @return BuildRoute[] Returns an array of BuildRoute objects
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
    public function findOneBySomeField($value): ?BuildRoute
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
