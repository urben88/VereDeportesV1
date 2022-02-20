<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Solicita;
use App\Entity\User;
use App\Form\SolicitaType;
use App\Service\FechasService;
use App\Service\NavService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


  /**
     * @Route("/user")
  */
class UserController extends AbstractController
{


    public function __construct(EntityManagerInterface $em, NavService $nav, FechasService $_fechas)
    {
        $this->em = $em;
        $this->nav = $nav;
        $this->_fecha = $_fechas;
    }
    /**
     * @Route("/solicitar", name="solicitar")
     */
    public function solicita(): Response
    {

        if($this->getUser()->getCapitan() == 1){
           return $this->redirectToRoute('home');
        }
        $equipoRepository = $this->em->getRepository(Equipo::class);
        $equipos = $equipoRepository->findAll();
       
        if(count($this->getUser()->getSolicitas()) != 0){
            $solicitudes = true;

        }else{
            $solicitudes = false;
        }
        
        return $this->render('user/solicitar.html.twig',[
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'equipos'=>$equipos,
            'user'=>$this->getUser(),
            'solicitudes'=>$solicitudes
            
        ]);
    }
    /**
     * @Route("/solicitar/equipo/{id}",name="solicitarEquipo")
     */
    public function solicitarEquipo($id){
        $user = $this->getUser();
        //? Cojo el atributo id del objeto json.
        $solicitud = new Solicita();
        $solicitud->setIdUsuario($user);
        $equipo = $this->em->getRepository(Equipo::class)->find($id);
        $solicitud->setAceptado(0);
        $solicitud->setIdEquipo($equipo);
        $solicitud->setFechaSolicitud($this->_fecha->getAct());
        $this->em->persist($solicitud);
        $this->em->flush();
        return $this->redirectToRoute('solicitar');
    
    }
      /**
     * @Route("/solicitar/equiporemove/{id}",name="solicitarRemoveEquipo")
     */
    public function solicitarEquipoRemove($id){
        $user = $this->getUser();
        //? Cojo el atributo id del objeto json.
        $solicitud = $this->em->getRepository(Solicita::class)->findOneBy(['id_equipo'=>$id,'id_usuario'=>$user->getId()]);
        $this->em->remove($solicitud);
        $this->em->flush();
        return $this->redirectToRoute('solicitar');
    
    }

    /**
     * @Route("/controlSolicitud", name="controlSolicitud")
     */
    public function aceptarSolicitud(){
        $user = $this->getUser();
        $equipo = $user->getEquipo();
        $solicitudes = $this->em->getRepository(Solicita::class)->findBy(['id_equipo'=>$equipo->getId(),'aceptado'=>'0']);


        return $this->render('user/controlSolicitud.html.twig',[
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'user'=>$this->getUser(),
            'solicitudes'=>$solicitudes,
            'equipo'=>$equipo
        ]);
    }
      /**
     * @Route("/removeSolicitud/{id}", name="removeSolicitud")
     */
    public function removeSolicitud($id){
        $user = $this->getUser();
        $solicitud = $this->em->getRepository(Solicita::class)->find($id);
        $this->em->remove($solicitud);
        $this->em->flush();
        $this->addFlash('rechazado',"Se ha rechazado la solicitud");
        return $this->redirectToRoute('controlSolicitud');
    }


    /**
     * @Route("/aceptSolicitud/{id}", name="aceptSolicitud")
     */
    public function aceptSolicitud($id){
        $user = $this->getUser();
        $solicitud = $this->em->getRepository(Solicita::class)->find($id);
        $solicitud->setAceptado(1);
        $this->em->persist($solicitud);
        $this->em->flush();
        $this->addFlash('exito','Se ha aceptado la solicitud');
        return $this->redirectToRoute('controlSolicitud');
    }

   



}
