<?php

namespace App\Controller;

use App\Entity\Liga;
use App\Service\NavService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public $usuario;
    public function __construct( NavService $nav, EntityManagerInterface $em)
    {
        $this->usuario = $nav->getDataNav();
        $this->em = $em;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        $ligas = $this->em->getRepository(Liga::class)->findAll();
        $ligasactivas = $this->em->getRepository(Liga::class)->findBy(['status'=>1]);
        return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'email' =>$this->usuario['email'],
                'admin'=>$this->usuario['admin'],
                'ligas'=>$ligas,
                'ligasact'=>$ligasactivas
        ]);
       
    }
      /**
     * @Route("/menu", name="irMenu")
     */
    public function controlMenu()
    {
        $user = $this->getUser();
        if($user){
            $rol = $user->getRoles()[0];
            if($rol == "ROLE_USER"){
                if($user->getCapitan()){
                    return $this->redirectToRoute('home');
                }else{
                    return $this->redirectToRoute('home');
                }
              
            }elseif($rol == "ROLE_ADMIN"){
                return $this->redirectToRoute('home');
            }else{
                return $this->redirectToRoute('home');
            }
        }else{
            return $this->redirectToRoute('home');
        }
        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'HomeController',
        // ]);
    }
}
