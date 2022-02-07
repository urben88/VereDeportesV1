<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        $admin = false;
        if($this->getUser()){
            if($this->getUser()->getRoles()[0] == "ROLE_USER"){
                $admin = false;
            }else{
                $admin = true;
            }
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'email'=>$this->getUser()->getEmail(),
                'admin'=>$admin
            ]); 
        }else{
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'email' => false,
                'admin'=>$admin
            ]);
        }
       
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
