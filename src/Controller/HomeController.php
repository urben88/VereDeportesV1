<?php

namespace App\Controller;

use App\Service\NavService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public $usuario;
    public function __construct( NavService $nav)
    {
        $this->usuario = $nav->getDataNav();
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'email' =>$this->usuario['email'],
                'admin'=>$this->usuario['admin']
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
                return $this->redirectToRoute('home');
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
