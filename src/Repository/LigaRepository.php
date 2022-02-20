<?php

namespace App\Repository;

use App\Entity\Liga;
use App\Service\FechasService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Liga|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liga|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liga[]    findAll()
 * @method Liga[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigaRepository extends ServiceEntityRepository
{
    public $_fecha;
    public function __construct(ManagerRegistry $registry, FechasService $_fecha)
    {
        parent::__construct($registry, Liga::class);
        $this->_fecha = $_fecha;

    }

   
    public function countLigas($deporte){
        if($deporte == 'baloncesto'){
            return count($this->_em->getRepository(Liga::class)->findBy(['deporte'=>'baloncesto']));
        }else{
            return count($this->_em->getRepository(Liga::class)->findBy(['deporte'=>'futbol']));
        }
    }
    public function canStart($deporte){
        if($deporte == 'baloncesto'){
            if( count($this->_em->getRepository(Liga::class)->findBy(array('deporte'=>'baloncesto','status'=>'true'))) >= 1){
                return false;
            }else{
                return true;
            }
   
        }else{
            if( count($this->_em->getRepository(Liga::class)->findBy(array('status'=>'true','deporte'=>'futbol'))) >= 1){
                return false;
            }else{
                return true;
            }
   
        }
    }
    public function isValida(\DateTime $ligadada, $deporte){
        $ligas = $this->_em->getRepository(Liga::class)->findBy(['deporte'=>$deporte]);
        
        foreach( $ligas as $liga){
            if($liga->getFecha() == $ligadada){
                return false;
            }
        }
        return true;
       
    }
    
    public function start($id){
        $liga = $this->_em->getRepository(Liga::class)->findOneBy(['id'=>$id]);
        if($this->canStart($liga->getDeporte())){
            $this->createPartidos($liga);
            return true;
        }else{
            return false;
        }
    }

    public function createPartidos(Liga $liga){
        $equiposLiga = $liga->getEquipos();
        $numEquipos = count($equiposLiga);
        if( $numEquipos % 2 != 0){
            arary_pop($equiposLiga);
        }
        $sabadoStart = $this->_fecha->nextSaturday($liga->getFecha());

        //? Saber cuantos partidos se van a jugar en la liga
        
    }
    public function isValidaStart(){

    }

    // /**
    //  * @return Liga[] Returns an array of Liga objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Liga
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
