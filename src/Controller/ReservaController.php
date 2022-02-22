<?php

namespace App\Controller;

use App\Entity\Campo;
use App\Entity\Reserva;
use App\Entity\User;
use App\Form\ReservaType;
use App\Service\FechasService;
use App\Service\NavService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
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
        $reservas = $this->em->getRepository(Reserva::class)->findBy(['id_usuario'=>$user->getId()]);
        $reserva = new Reserva();
        $form = $this->createForm(ReservaType::class,$reserva);
        $form->handleRequest($request);

        $resevasSemana = 0;
      
        if($form->isSubmitted() && $form->isValid()){
            // //? Saber si hay 3 reservas esa semana
             foreach ($reservas as $reservaext) {
                 if($reservaext->getFecha()->format("W") == $form['fecha']->getData()->format("W")){
                   $resevasSemana++;
                  }
              }

            if($form['campos']->getData()->getDeporte() == $user->getEquipo()->getDeporte()){
                if($resevasSemana == 3){
                    $this->addFlash('error',"No puedes tener mas de 3 reservas en una semana");
                }else{
                    $fecha = $form['fecha']->getData();
                    if($this->_fecha->isAct($fecha)){
                        $fechacaduca = $this->_fecha->add90($fecha);
                        if($this->em->getRepository(Reserva::class)->isValida($fecha,$fechacaduca,$form['campos']->getData())){

                            $fechacreacion = new \DateTime('now');
                            $campo = $form['campos']->getData();
                            $admin = $this->em->getRepository(User::class)->getAdminRand();
                            if($this->_fecha->isSemana($fecha)){
                                $reserva->setIdUsuario($user);
                                $reserva->setIdCampo($campo);
                                $reserva->setIdProfesor($admin);
                                $reserva->setFechaCaduca($fechacaduca);
                                $reserva->setFechaCreacion($fechacreacion);
                                $this->em->persist($reserva);
                                $this->em->flush();

                            }else{
                                $this->addFlash('error',"No puedes crear una reserva un fin de semana");
                            }
                        }else{
                                $this->addFlash('error',"Ya existe una reserva para esa hora y campo");
                        }
                    }else{
                        $this->addFlash('error',"No puedes hacer una reserva en el pasado");
                    }
                }
            }else{
                $this->addFlash('error',"No puedes seleccionar un campo que no es de tu deporte");
            }
            
        } 

        return $this->render('reserva/create.html.twig', [
            'controller_name' => 'ReservaController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'formulario'=>$form->createView(),
            'campos'=> $campos,
            'reservas'=>$reservas,
            '_fecha'=>$this->_fecha
            // 'fecha' => $fecha->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @Route("/removeReserva",name="removeReserva")
     */
    public function removeReserva(Request $response){
        $json= $response->get('id',null);
        $data = json_decode($json);
        $reserva = $this->em->getRepository(Reserva::class)->findOneBy(['id'=>$json]);
        $this->em->remove($reserva);
        $this->em->flush();
        return $this->json(["status"=>"exito","id"=>$json]);
       
    }
    /**
     * @Route("/adminReserva",name="adminReserva")
     */
    public function adminReservas(){
        $user = $this->getUser();
        $reservasProfesor = $user->getReservasProfesor();
        $admins = $this->em->getRepository(User::class)->getAdmins();

        return $this->render('reserva/admin.html.twig', [
            'controller_name' => 'ReservaController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'reservas'=>$reservasProfesor,
            'admins'=>$admins,
            '_fecha'=>$this->_fecha
            // 'fecha' => $fecha->format('Y-m-d H:i:s')
        ]);

    }
    /**
     * @Route("/changeVigilancia/{idr}/{idp}",name="changeVigilancia")
     */
    public function changeVigilancia($idr,$idp){
        $reserva = $this->em->getRepository(Reserva::class)->find($idr);
        $profesor = $this->em->getRepository(User::class)->find($idp);
        $reserva->setIdProfesor($profesor);
        $this->em->persist($reserva);
        $this->em->flush();
        return $this->redirectToRoute('adminReserva');
    }

    /**
     * @Route("/showReservasEquipo",name="showReservasEquipo")
     */
    public function showReservasEquipo(){
      
        $user = $this->getUser();
        $equipo = $user->getEquipo();
        $capitan = $this->em->getRepository(User::class)->findOneBy(['capitan'=>1,'equipo'=>$equipo->getId()]);
        $reservas = $this->em->getRepository(Reserva::class)->findBy(['id_usuario'=>$capitan->getId()]);
    
        return $this->render('reserva/showJugador.html.twig', [
            'controller_name' => 'ReservaController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'reservas'=>$reservas,
            "_fecha"=>$this->_fecha
            // 'fecha' => $fecha->format('Y-m-d H:i:s')
        ]);

    }

}
