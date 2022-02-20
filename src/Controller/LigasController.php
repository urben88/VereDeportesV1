<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Liga;
use App\Entity\User;
use App\Form\LigaType;
use App\Service\FechasService;
use App\Service\NavService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligas")
 */
class LigasController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, NavService $nav, FechasService $_fechas)
    {
        $this->em = $em;
        $this->nav = $nav;
        $this->_fecha = $_fechas;
    }

    /**
     * @Route("/createLiga", name="createLiga")
     */
    public function createLiga(Request $request): Response
    {
        $liga = new Liga();
        $form = $this->createForm(LigaType::class,$liga);
        $form->handleRequest($request);
        $ligas = $this->em->getRepository(Liga::class)->findAll();
        if($form->isSubmitted() && $form->isValid()){
          if($this->_fecha->isAct($form['fecha']->getData())){
            if( $this->em->getRepository(Liga::class)->isValida($form['fecha']->getData(),$form['deporte']->getData())){
                $liga->setDeporte($form['deporte']->getData());
                $liga->setStatus(0);
                $liga->setFechaCreacion(new \DateTime('now'));
                $this->em->persist($liga);
                $this->em->flush(); 
            }else{
                $this->addFlash('error',"No puedes crear una liga del mismo deporte el mismo dia");
            }
            
          }else{
              $this->addFlash('error',"La fecha que has introducido es del pasado");
          }
        
        }
        return $this->render('ligas/create.html.twig', [
            'controller_name' => 'LigasController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'formulario'=>$form->createView(),
            'ligas'=>$ligas
        ]);
    }
    /**
     * @Route("/showLiga", name="showLiga")
     */
    public function showLiga(){
        $ligas = $this->em->getRepository(Liga::class)->findAll();
        
         return $this->render('ligas/show.html.twig', [
            'controller_name' => 'LigasController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'ligas'=>$ligas
        ]);
    }
     
    /**
     * @Route("/solicitarLiga", name="solicitarLiga")
     */
    public function solicitarLiga(){
        $ligas = $this->em->getRepository(Liga::class)->findAll();
        return $this->render('ligas/solicitar.html.twig', [
            'controller_name' => 'LigasController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'ligas'=>$ligas,
            'equipo'=>$this->getUser()->getEquipo()
        ]);
    }

    /**
     * @Route("/solicitarUnaLiga/{id}", name="solicitarUnaLiga")
     */
    public function solicitarUnaLiga($id){
        $equipo  = $this->getUser()->getEquipo();
        $liga = $this->em->getRepository(Liga::class)->find($id);
        $equipo->addLiga($liga);
        $this->em->persist($equipo);
        $this->em->flush();
        return $this->redirectToRoute('solicitarLiga');

    }

    /**
     * @Route("/removesolicitarUnaLiga/{id}", name="removesolicitarUnaLiga")
     */
    public function removesolicitarUnaLiga($id){
        $equipo  = $this->getUser()->getEquipo();
        $liga = $this->em->getRepository(Liga::class)->find($id);
        $liga->removeEquipo($equipo);
        $this->em->persist($liga);
        $this->em->flush();
        return $this->redirectToRoute('solicitarLiga');

    }
    
    
    /**
     * @Route("/startLiga/{id}", name="startLiga")
     */
    public function startLiga($id){
        $liga = $this->em->getRepository(Liga::class)->find($id);
        if(count($liga->getEquipos()) < 10){
            $this->addFlash('error', "No puedes empezar una liga con menos de 10 equipos");
        }else{
            if($this->em->getRepository(Liga::class)->start($id)){
                
            }else{
                $this->addFlash('error',"No puedes empezar la liga");
            }
          
        }
        return $this->redirectToRoute("createLiga");
    }



}
