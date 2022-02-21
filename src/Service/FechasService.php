<?php


namespace App\Service;

use DateInterval;
use DateTime;
use Symfony\Component\Security\Core\Security;

class FechasService
{

    public $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getAct(){
        $objDateTime = new \DateTime('NOW');
        return $objDateTime;
    
    }

    public function add90(\DateTime $fechapartido){
        $sumar = clone $fechapartido;
        return $sumar->modify("+90 minute");

    }
    public function isSemana(\DateTime $fechapartido){

         $finde = ["Sat","Sun"];
         $semana = true;
         foreach ($finde as $dia){
            if($fechapartido->format("D") == $dia){
                $semana = false;
            }
         }
         return $semana;
     }
     public function isSabado(\DateTime $fechapartido){
        $dia = "Sat";
        $sabado = false;
           if($fechapartido->format("D") == $dia){
               $semana = true;
           }else{
                $sabado = false;
           }
         return $sabado;
    }

     public function isAct(\Datetime $fecha){
        $actual = new \DateTime('now');
        if($fecha < $actual){
            return false;
        }else{
            return true;
        }
     }
     
     function inInterval(Datetime $fecha, Datetime $inicio, Datetime $fin){
        if($fecha > $inicio && $fecha < $fin)
        {
            return true;
        }else{
            return false;
        }
      }

    function nextSaturday(DateTime $fecha){
        $arraydias = ["Mon"=>5,"Tue"=>4,"Wed"=>3,"Thu"=>2,"Fri"=>1,"Sat"=>0,"Sun"=>6];
        $dia = null;
        foreach ($arraydias as $key => $value) {
            if($fecha->format("D") == $key){
                $dia = $value;
            }
        }
        $clon = clone $fecha;
    
        return $clon->modify("+".$dia."days");
    
    }

}