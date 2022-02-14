<?php

namespace App\Controller;

use App\Entity\Solicita;
use App\Form\SolicitaType;
use App\Service\NavService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
  /**
     * @Route("/user")
  */
class UserController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, NavService $nav)
    {
        $this->em = $em;
        $this->nav = $nav;
    }
    /**
     * @Route("/solicitar", name="solicita")
     */
    public function solicita(): Response
    {
        $admin = false;
       
        return $this->render('user/solicitar.html.twig',[
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin']
        ]);
    }
}
