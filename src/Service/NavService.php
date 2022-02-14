<?php


namespace App\Service;

use Symfony\Component\Security\Core\Security;

class NavService
{

    public $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getDataNav(){
        $admin = false;

        if($this->security->getUser()){
            if($this->security->getUser()->getRoles()[0] == "ROLE_USER"){
                $admin = false;
            }else{
                $admin = true;
            }
            return ['email'=>$this->security->getUser()->getEmail(),'admin'=>$admin];
    
        }else{
            return ['email'=>false,'admin'=>$admin];
        }
    
    }


}