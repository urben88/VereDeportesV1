<?php

namespace App\Repository;

use App\Entity\Campo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Campo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campo[]    findAll()
 * @method Campo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campo::class);
    }

    public function getCampoRand($deporte){
        $campos = $this->_em->getRepository(Campo::class)->findBy(['deporte'=>$deporte]);
        $total = count($campos);
        $rand = rand(0,$total-1);
        return  $campos[$rand];

    }

    // /**
    //  * @return Campo[] Returns an array of Campo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Campo
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
