<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Forms
use App\Form\CampoType;

//Entity
use App\Entity\Campo;

//Obtener respuesta
use Symfony\Component\HttpFoundation\Request;

/**
     * @Route("/campo")
*/
class CampoController extends AbstractController
{
    /**
     * @Route("/create")
    */
    public function create(Request $request): Response
    {
        $admin = false;
        $campo = new Campo;
        $form = $this->createForm(CampoType::class,$campo);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          
        }

        if($this->getUser()){
            if($this->getUser()->getRoles()[0] == "ROLE_USER"){
                $admin = false;
            }else{
                $admin = true;
            }

            return $this->render('campo/create.html.twig', [
                'controller_name' => 'HomeController',
                'email'=>$this->getUser()->getEmail(),
                'admin'=>$admin,
                'formulario'=>$form->createView()
            ]); 
        }else{
            return $this->render('campo/create.html.twig', [
                'controller_name' => 'HomeController',
                'email' => false,
                'admin'=>$admin,
                'formulario'=>$form->createView()
            ]);
        }
    }
}
