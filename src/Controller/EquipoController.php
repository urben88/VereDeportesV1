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
use App\Repository\EquipoRepository;

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
            if(!$this->em->getRepository(Equipo::class)->existName($form['nombre']->getData())){
            $equipo->setCapitan(true);
            $equipo->setDeporte($form['deporte']->getData());
            $capitan = $form['capitan']->getData();
            $capitan->setCapitan(true);
            $solicitudesCapi = $this->em->getRepository(Solicita::class)->findBy(['id_usuario'=>$capitan->getId()]);
            if(count($solicitudesCapi)!= 0){
                foreach ($solicitudesCapi as $solicitudd) {
                    $this->em->remove($solicitudd);
                    $this->em->flush();
                }
            }

            $solicitud->setIdUsuario($capitan);
            $solicitud->setAceptado(1);
            $solicitud->setFechaSolicitud($this->_fechas->getAct());

            $this->em->persist($equipo);
          
            $this->em->flush();

            $solicitud->setIdEquipo($equipo);
            $capitan->setEquipo($equipo);
            $this->em->persist($solicitud);
            $this->em->persist($capitan);
            $this->em->flush();
            }else{
                $this->addFlash('error',"El nombre ya exite");
            }



        }
        $repositorioEquipo = $this->em->getRepository(Equipo::class);
        $equipos = $repositorioEquipo->findAll();
            
        return $this->render('equipo/create.html.twig', [
                'controller_name' => 'HomeController',
                'email'=>$this->usuario['email'],
                'admin'=>$this->usuario['admin'],
                'formulario'=>$form->createView(),
                'equipos'=>$equipos
            ]); 
       
    }
    /**
     * @Route("/showEquipo",name="showEquipo")
     */
    public function verEquipo(){

        $equipo = $this->getUser()->getEquipo();
        $capitan = $this->em->getRepository(User::class)->findOneBy(['capitan'=>"1",'equipo'=>$this->getUser()->getEquipo()->getId()]);
        return $this->render('equipo/show.html.twig', [
            'controller_name' => 'HomeController',
            'email'=>$this->usuario['email'],
            'admin'=>$this->usuario['admin'],
            'equipo'=> $equipo,
            'capitan'=>$capitan
        ]); 
    }

    /**
     * @Route("/showEquipo/{id}",name="showEquipoId")
     */
    public function showEquipoId($id){

        $equipo = $this->em->getRepository(Equipo::class)->find($id);
        $capitan = $this->em->getRepository(User::class)->findOneBy(['capitan'=>"1",'equipo'=>$equipo->getId()]);
        return $this->render('equipo/showId.html.twig', [
            'controller_name' => 'HomeController',
            'email'=>$this->usuario['email'],
            'admin'=>$this->usuario['admin'],
            'equipo'=> $equipo,
            'capitan'=>$capitan
        ]); 
    }


    /**
     * @Route("/showEquipos",name="showEquipos")
     */
    public function showEquipos(){

        $equipos = $this->em->getRepository(Equipo::class)->findAll();
        // $capitan = $this->em->getRepository(User::class)->findOneBy(['capitan'=>"1",'equipo'=>$equipo->getId()]);
        
        return $this->render('equipo/showEquipos.html.twig', [
            'controller_name' => 'HomeController',
            'email'=>$this->usuario['email'],
            'admin'=>$this->usuario['admin'],
            'equipos'=> $equipos,
            // 'capitan'=>$capitan
        ]); 
    }
}
