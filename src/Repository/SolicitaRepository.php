<?php

namespace App\Repository;

use App\Entity\Solicita;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Solicita|null find($id, $lockMode = null, $lockVersion = null)
 * @method Solicita|null findOneBy(array $criteria, array $orderBy = null)
 * @method Solicita[]    findAll()
 * @method Solicita[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Solicita::class);
    }

    // /**
    //  * @return Solicita[] Returns an array of Solicita objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Solicita
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
