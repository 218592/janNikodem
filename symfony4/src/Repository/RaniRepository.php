<?php

namespace App\Repository;

use App\Entity\Rani;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rani|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rani|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rani[]    findAll()
 * @method Rani[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaniRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rani::class);
    }

    // /**
    //  * @return Rani[] Returns an array of Rani objects
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
    public function findOneBySomeField($value): ?Rani
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
