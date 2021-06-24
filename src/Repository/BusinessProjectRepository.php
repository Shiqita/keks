<?php

namespace App\Repository;

use App\Entity\BusinessProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BusinessProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessProject[]    findAll()
 * @method BusinessProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BusinessProject::class);
    }

    // /**
    //  * @return BusinessProject[] Returns an array of BusinessProject objects
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
    public function findOneBySomeField($value): ?BusinessProject
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
