<?php

namespace App\Repository;

use App\Entity\Campo;
use App\Entity\Equipo;
use App\Entity\Liga;
use App\Entity\Partido;
use App\Entity\User;
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
    public function isValida(\DateTime $empieza, $deporte){
        $ligas = $this->_em->getRepository(Liga::class)->findBy(["deporte"=>$deporte]);
        $sabado = $this->_fecha->nextSaturday($empieza);
        
        $fechamin = clone $sabado;
        $semanasmin =  7*(6-1);
        //? Fecha que terminaria con 4 equipos
        $fechamin =  $fechamin->modify("+".($semanasmin)."days");
        $fechamax = clone $sabado;
        $semanasmax=  7*(18-1);
         //? Fecha que terminaria con 10 equipos
        $fechamax = $fechamax->modify("+".($semanasmax)."days");
    
        
        foreach( $ligas as $liga){
            $sabadoliga = $this->_fecha->nextSaturday($liga->getFecha());
            $otramax = clone $liga->getFecha();
            $otramax = $otramax->modify("+".($semanasmax)."days");
           
            if($empieza >= $liga->getFecha() && $empieza <= $liga->getFechaTermina()){
                return [false,"No puedes crear una liga en un periodo de tiempo de una liga activa","No cambiar la fecha de una liga en un periodo de tiempo de una liga activa"];
            }else if(($sabadoliga >= $fechamin && $sabadoliga <= $fechamax) || ($otramax >=  $fechamin && $otramax <= $fechamax)){
                return [false,"No puedes crear una liga en un periodo de tiempo de otra liga (Las ligas no comenzadas tienen por defecto una duración de 17 semanas)","No puedes modificar la fecha de la liga en un periodo de tiempo de otra liga (Las ligas no comenzadas tienen por defecto una duración de 17 semanas)"];
            }
        }
        
        return [true,"Es correcta"];
       
    }
    
    public function start($id){
        $liga = $this->_em->getRepository(Liga::class)->findOneBy(['id'=>$id]);
        // if($this->canStart($liga->getDeporte())){
            $resul = $this->createPartidos($liga);
            return $resul;
        // }else{
        //     return [false,"No puedes comenzar la liga porque la fecha de inicio concuerda con una ya existente"];
        // }
    }

    public function createPartidos(Liga $liga){
        $equiposLiga = $liga->getEquipos();
        $numEquipos = count($equiposLiga);
        if( $numEquipos % 2 != 0){
            $liga->removeEquipo($equiposLiga[$numEquipos-1]);
            $this->_em->persist($liga);
            $this->_em->flush();
        }

        //? Saber cuantos partidos se van a jugar en la liga
        $partidosTotales = count($equiposLiga)*(count($equiposLiga)-1);
        $partidosPorVuelta = $partidosTotales /2;
        $partidosPorDia = count($equiposLiga)/2;
        $diasNecesitaTotal = $partidosTotales/$partidosPorDia;
        $diasNecesitaPorVuelta = $diasNecesitaTotal/2;

        //TODO Partidos IDA Fila = PrtidosEnUnDia Columnas = sabados de una ida
        $listaida = [];
        $equipo = 0;
        for ($f=0; $f < $diasNecesitaPorVuelta  ; $f++) { 
            for ($c=0; $c < $partidosPorDia; $c++) { 
                if($equipo == count($equiposLiga)-1){
                    $equipo = 0;
                }
                $listaida[$f][$c] = $equiposLiga[$equipo];
                $equipo++;
            }
        }
        $equipo = count($equiposLiga)-2;
        $par = 1;
        for ($f=0; $f < $diasNecesitaPorVuelta; $f++) { 
            for ($c=0; $c < $partidosPorDia; $c++) { 
                if($equipo < 0){
                $equipo = count($equiposLiga)-2;
                }
                if($c == 0){
                    if($par){
                        $listaida[$f][$c] = [$equiposLiga[count($equiposLiga)-1],$listaida[$f][$c]];
                        $par = 0;
                    }else{
                        $listaida[$f][$c] = [$listaida[$f][$c],$equiposLiga[count($equiposLiga)-1]];
                        $par=1;
                    }
                    
                
                }else{
                    $listaida[$f][$c] = [$listaida[$f][$c], $equiposLiga[$equipo]];
                    $equipo--;
                }
            
            }
        }

        //TODO Partidos vuelta
        $listavuelta =[];
        for($f=0; $f < $diasNecesitaPorVuelta ; $f++) { 
            for ($c=0; $c < $partidosPorDia; $c++) { 
            $listavuelta[$f][$c] = [$listaida[$f][$c][1],$listaida[$f][$c][0]];
            }
        }

        //TODO Uno los dos arrays
        $listatotal = array_merge($listaida,$listavuelta);

        //? Generar los Sabados
        $sabadoStart = $this->_fecha->nextSaturday($liga->getFecha());
        $sabados = new \DatePeriod($sabadoStart,new \DateInterval("P7D"),$diasNecesitaTotal-1);


        //? Compruebo que no hay ninguna liga en el mismo periodo de tiempo
        $diaacaba = clone $sabadoStart;
        $diasfinal = 7 * ($diasNecesitaTotal-1);
        $diaacaba = $diaacaba->modify("+".($diasfinal+1)."days");
        if(!$this->isValidaStart($liga,$sabadoStart,$diaacaba)){
            return [false,"La liga no se ha generado porque ya hay una activa en ese periodo de tiempo"];
        } 

        //? Comienzo a crear los partidos

        foreach ($sabados as $key => $dia) {
            $dia1 = clone $dia->modify("+16 hours");
            for ($i=0; $i < $partidosPorDia; $i++) { 
                $fechaEmpieza = clone $dia1;
                $dia2 = clone $dia1->modify("+90 minutes");
                $equipo1 = $listatotal[$key][$i][0];
                $equipo2 = $listatotal[$key][$i][1];
                $ligaid = $liga->getId();
                $profesor = $this->_em->getRepository(User::class)->getAdminRand();
                $campo = $this->_em->getRepository(Campo::class)->getCampoRand($liga->getDeporte());
                $fechaacaba = clone $dia2;
                //* Pongo datos partido
                $partido = new Partido();
                $partido->setIdLiga($liga);
                $partido->setIdProfesor($profesor);
                $partido->setIdCampo($campo);
                $partido->setEquipo1($equipo1->getId());
                $partido->setEquipo2($equipo2->getId());
                $partido->setFechaPartido($fechaEmpieza);
                $partido->setFechaAcaba($fechaacaba);
                $this->_em->persist($partido);
                $liga->setStatus(1);
                $this->_em->persist($liga);
                $this->_em->flush();
                
            }
        }
        $liga->setFechaTermina($diaacaba);
        $this->_em->persist($liga);
        $this->_em->flush();
        return [true,"La liga se ha generado con sus partidos"];

        



        
    }
    public function isValidaStart(Liga $liga, \DateTime $empieza, \DateTime $acaba){
        $ligas = $this->_em->getRepository(Liga::class)->findBy(["deporte"=>$liga->getDeporte()]);
        foreach( $ligas as $liga){
            if( 
            ($empieza >= $liga->getFechaCreacion() && $empieza <= $liga->getFechaTermina())
            || ($acaba >= $liga->getFechaCreacion() && $acaba <= $liga->getFechaTermina())
            ){
                return false;
            }
        }
        return true;
    }
    
    function getEquipo($id){
        $equipo = $this->_em->getRepository(Equipo::class)->find($id);
        return $equipo;
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
