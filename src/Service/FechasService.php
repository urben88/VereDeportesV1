<?php


namespace App\Service;

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


}