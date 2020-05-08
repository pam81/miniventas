<?php

namespace App\Repository;

use App\Entity\BuyProduct;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @method BuyProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuyProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuyProduct[]    findAll()
 * @method BuyProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuyProductRepository extends QueryBuilder
{
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct(BuyProduct::class, $registry, $logger);
    }

    // /**
    //  * @return BuyProduct[] Returns an array of BuyProduct objects
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
    public function findOneBySomeField($value): ?BuyProduct
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
