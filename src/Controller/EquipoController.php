<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Solicita;
use App\Form\EquipoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Request
use Symfony\Component\HttpFoundation\Request;

//Servicios
use App\Service\NavService;
use App\Service\FechasService;

//Entidades
use App\Entity\User;

/**
 * @Route("/equipo")
 */
class EquipoController extends AbstractController
{


    public $usuario;
    public function __construct(EntityManagerInterface $em, NavService $nav, FechasService $_fechas)
    {
        $this->em = $em;
        $this->usuario = $nav->getDataNav();
        $this->_fechas = $_fechas;

    }

    /**
     * @Route("/create", name="equipo")
     */
    public function create(Request $request): Response
    {
        $equipo = new Equipo;
        $solicitud = new Solicita;
        $form = $this->createForm(EquipoType::class,$equipo);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $equipo->setCapitan(true);
            $equipo->setDeporte($form['deporte']->getData());
            $capitan = $form['capitan']->getData();
            $capitan->setCapitan(true);
            $solicitud->setIdUsuario($capitan);
            $solicitud->setAceptado(1);
            $solicitud->setFechaSolicitud($this->_fechas->getAct());

            $this->em->persist($equipo);
            $this->em->persist($capitan);
            $this->em->flush();

            $solicitud->setIdEquipo($equipo);
            $this->em->persist($solicitud);
            $this->em->flush();




        }
            return $this->render('equipo/create.html.twig', [
                'controller_name' => 'HomeController',
                'email'=>$this->usuario['email'],
                'admin'=>$this->usuario['admin'],
                'formulario'=>$form->createView()
            ]); 
       
    }
}
