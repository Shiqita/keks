<?php

namespace App\Repository;

use App\Entity\Prototype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prototype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prototype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prototype[]    findAll()
 * @method Prototype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrototypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prototype::class);
    }

    // /**
    //  * @return Prototype[] Returns an array of Prototype objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Prototype
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
