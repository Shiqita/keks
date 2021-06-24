<?php

namespace App\Repository;

use App\Entity\BusinessIdea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BusinessIdea|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessIdea|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessIdea[]    findAll()
 * @method BusinessIdea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessIdeaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BusinessIdea::class);
    }

    // /**
    //  * @return BusinessIdea[] Returns an array of BusinessIdea objects
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
    public function findOneBySomeField($value): ?BusinessIdea
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
