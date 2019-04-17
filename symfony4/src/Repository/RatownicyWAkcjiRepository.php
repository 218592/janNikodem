<?php

namespace App\Repository;

use App\Entity\RatownicyWAkcji;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RatownicyWAkcji|null find($id, $lockMode = null, $lockVersion = null)
 * @method RatownicyWAkcji|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatownicyWAkcji[]    findAll()
 * @method RatownicyWAkcji[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatownicyWAkcjiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RatownicyWAkcji::class);
    }

    // /**
    //  * @return RatownicyWAkcji[] Returns an array of RatownicyWAkcji objects
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
    public function findOneBySomeField($value): ?RatownicyWAkcji
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
