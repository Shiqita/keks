<?php

namespace App\Repository;

use App\Entity\OrderCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderCategory[]    findAll()
 * @method OrderCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderCategory::class);
    }

    // /**
    //  * @return OrderCategory[] Returns an array of OrderCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderCategory
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
