<?php

namespace App\Repository;

use App\Entity\Grape;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Grape|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grape|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grape[]    findAll()
 * @method Grape[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrapeRepository extends QueryBuilder
{
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct(Grape::class, $registry, $logger);
    }

    // /**
    //  * @return Grape[] Returns an array of Grape objects
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
    public function findOneBySomeField($value): ?Grape
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
