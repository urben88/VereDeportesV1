<?php

namespace App\Repository;

use App\Entity\Campo;
use App\Entity\Reserva;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserva[]    findAll()
 * @method Reserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserva::class);
    }


    //! Falta hacer comprobación de intervalo de tiempo Solo da respeusta si coincide exactamente
    public function isValida(\DateTime $reservadada, \DateTime $fechacaduca, Campo $campo){
        $reservas = $this->_em->getRepository(Reserva::class)->findBy(["id_campo"=>$campo->getId()]);
        
        foreach( $reservas as $reserva){
            if( 
            ($reservadada >= $reserva->getFecha() && $reservadada <= $reserva->getFechaCaduca())
            || ($fechacaduca >= $reserva->getFecha() && $fechacaduca <= $reserva->getFechaCaduca())
            ){
                return false;
            }
        }
        return true;
       
    }

    // /**
    //  * @return Reserva[] Returns an array of Reserva objects
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
    public function findOneBySomeField($value): ?Reserva
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
