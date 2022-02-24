<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Liga;
use App\Entity\Partido;
use App\Entity\User;
use App\Form\LigaType;
use App\Service\FechasService;
use App\Service\NavService;
use DateTime;
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $liga = new Liga();
        $form = $this->createForm(LigaType::class,$liga);
        $form->handleRequest($request);
        $ligas = $this->em->getRepository(Liga::class)->findAll();
        if($form->isSubmitted() && $form->isValid()){
          if($this->_fecha->isAct($form['fecha']->getData())){
              $esvalida =$this->em->getRepository(Liga::class)->isValida($form['fecha']->getData(),$form['deporte']->getData());
            if($esvalida[0]){
                $liga->setDeporte($form['deporte']->getData());
                $liga->setStatus(0);
                $liga->setFechaCreacion(new \DateTime('now'));
                $this->em->persist($liga);
                $this->em->flush(); 
            }else{
                $this->addFlash('error',$esvalida[0]);
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
            'ligas'=>$ligas,
            '_fecha'=>$this->_fecha
        ]);
    }
    /**
     * @Route("/showLiga", name="showLiga")
     */
    public function showLiga(){
        $ligas = $this->em->getRepository(Liga::class)->findAll();
        $ligasactivas = $this->em->getRepository(Liga::class)->findBy(['status'=>1]);
         return $this->render('ligas/show.html.twig', [
            'controller_name' => 'LigasController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'ligas'=>$ligas,
            'ligasact'=>$ligasactivas
        ]);
    }
     
    /**
     * @Route("/solicitarLiga", name="solicitarLiga")
     */
    public function solicitarLiga(){
        $this->denyAccessUnlessGranted('ROLE_CAPI');
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
        $this->denyAccessUnlessGranted('ROLE_CAPI');
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
        $this->denyAccessUnlessGranted('ROLE_CAPI');
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $liga = $this->em->getRepository(Liga::class)->find($id);
        if(count($liga->getEquipos()) < 4 || count($liga->getEquipos()) > 10){
            if(count($liga->getEquipos()) < 4){
                $this->addFlash('error', "No puedes empezar una liga con menos de 4 equipos");
            }
            if(count($liga->getEquipos()) > 10){
                $this->addFlash('error', "No puedes empezar la liga ya que como máximo solo pueden haber 10 equipos apuntados");
            }
            
        }else{
            $resul = $this->em->getRepository(Liga::class)->start($id);
            if($resul[0]){
                $this->addFlash('exito',$resul[1]);
            }else{
                $this->addFlash('error',$resul[1]);
            }
            // $this->em->getRepository(Liga::class)->start($id);
          
        }
        return $this->redirectToRoute("createLiga");
    }
     /**
     * @Route("/removeLiga/{id}", name="removeLiga")
     */
    public function removeLiga($id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $liga = $this->em->getRepository(Liga::class)->find($id);
        $this->em->remove($liga);
        $this->em->flush();
        return $this->redirectToRoute("createLiga");
    }
      /**
     * @Route("/changeLiga/{id}/{fecha}", name="changeLiga")
     */
    public function changeLiga($id,$fecha){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $liga = $this->em->getRepository(Liga::class)->find($id);
        $newFecha = new \DateTime($fecha);
        if($this->_fecha->isAct($newFecha)){
            $result = $this->em->getRepository(Liga::class)->isValida($newFecha,$liga->getDeporte());
            if($result[0]){
            
                $liga->setFecha($newFecha);
                $this->em->persist($liga);
                $this->em->flush(); 
            }else{
                 $this->addFlash("error",$result[2]);
            }
       
        }else{
            $this->addFlash("error","La nueva fecha debe de ser actual");
        }
        return $this->redirectToRoute("createLiga");
    }
    /**
     * @Route("/show/{id}",name="showOne")
     */
    public function showOne($id){
        $liga = $this->em->getRepository(Liga::class)->find($id);
        $repositorioliga = $this->em->getRepository(Liga::class);
        return $this->render('ligas/showone.html.twig', [
            'controller_name' => 'LigasController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'liga'=>$liga,
            'ligarepository'=>$repositorioliga
        ]);
    }
    /**
     * @Route("/setResultados", name="setResultados")
     */
    public function setResultados(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $partidos = $this->em->getRepository(Partido::class)->findBy(['id_profesor'=>$user->getId()]);
        $ligarepository = $this->em->getRepository(Liga::class);

        return $this->render('ligas/setResultados.html.twig', [
            'controller_name' => 'LigasController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'partidos'=>$partidos,
            'ligarepository'=>$ligarepository,
            '_fecha'=>$this->_fecha
        ]);
    }
    /**
     * @Route("/changeResultados/{id}/{local}/{visitante}",name="changeResultados")
     */
    public function changeResultados($id,$local,$visitante){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $partido = $this->em->getRepository(Partido::class)->find($id);
        if($local != 'null' && $visitante != 'null'){
            if($partido->getResulEquipo1() != null){
                $this->addFlash('exito',"Se ha modificado un resultado anteriormente puntuado con: local=".$partido->getResulEquipo1()." visitante=".$partido->getResulEquipo2()); 
            }else{
                $this->addFlash('exito',"Se ha añadido el nuevo resultado"); 
            }
            $partido->setResulEquipo1($local);
            $partido->setResulEquipo2($visitante);
            $this->em->persist($partido);
            $this->em->flush();
        }else{
            $this->addFlash('error',"Uno o las dos puntuaciones estan vacias");
        }
        return $this->redirectToRoute('setResultados');
        
    }



}
