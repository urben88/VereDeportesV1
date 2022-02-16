<?php

namespace App\Controller;

use App\Entity\Campo;
use App\Entity\Reserva;
use App\Form\ReservaType;
use App\Service\FechasService;
use App\Service\NavService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reserva")
 */
class ReservaController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, NavService $nav, FechasService $_fechas)
    {
        $this->em = $em;
        $this->nav = $nav;
        $this->_fecha = $_fechas;
    }

    /**
     * @Route("/createReserva",name="createReserva")
     */
    public function createReserva(Request $request): Response
    {
        // ! Datos necesarios para la reserva
        $user = $this->getUser();
        $campos = $this->em->getRepository(Campo::class)->findAll();
        
        $reserva = new Reserva();
        $form = $this->createForm(ReservaType::class,$reserva);
        $form->handleRequest($request);
        $fecha = "No hay fecha";
        if($form->isSubmitted() && $form->isValid()){
           $fecha = $form['fecha']->getData();
        } 

        return $this->render('reserva/create.html.twig', [
            'controller_name' => 'ReservaController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'formulario'=>$form->createView(),
            'campos'=> $campos,
            // 'fecha' => $fecha->format('Y-m-d H:i:s')
        ]);
    }
}
