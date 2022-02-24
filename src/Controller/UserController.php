<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Solicita;
use App\Entity\User;
use App\Form\SolicitaType;
use App\Form\UserType;
use App\Service\FechasService;
use App\Service\NavService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
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
        $equipossolicitados = $this->em->getRepository(Solicita::class)->findBy(["id_usuario"=>$user->getId()]);
        $equiposSoli = [];
        foreach ($equipossolicitados as $value) {
            $equiposSoli[] = $value->getIdEquipo();
        }
        return $this->render('user/solicitar.html.twig',[
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'equipos'=>$equipos,
            'user'=>$this->getUser(),
            'solicitudes'=>$solicitudes,
            'equipossoli'=>$equiposSoli
            
            
        ]);
    }
    /**
     * @Route("/solicitar/equipo/{id}",name="solicitarEquipo")
     */
    public function solicitarEquipo($id){
        $this->denyAccessUnlessGranted('ROLE_USER');
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
        $this->denyAccessUnlessGranted('ROLE_USER');
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
        $this->denyAccessUnlessGranted('ROLE_CAPI');
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
        $this->denyAccessUnlessGranted('ROLE_CAPI');
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
        $this->denyAccessUnlessGranted('ROLE_CAPI');
        $user = $this->getUser();
        $solicitud = $this->em->getRepository(Solicita::class)->find($id);
        $solicitante = $solicitud->getIdUsuario();
        $solicitante->setEquipo($user->getEquipo());
        $solicitud->setAceptado(1);
        $this->em->persist($solicitud);
        $this->em->persist($solicitante);
        $this->em->flush();
        $this->addFlash('exito','Se ha aceptado la solicitud');
        return $this->redirectToRoute('controlSolicitud');
    }

    /**
     * @Route("/registeradmin", name="registeradmin")
     */
    public function registeradmin(Request $request, UserPasswordEncoderInterface  $passwordEncoder)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form['Repeat_password']->getData() != $form['password']->getData()){
                $this->addFlash('error','Las constraseñas no son iguales');
            }else{
                // if($form['admin']->)
                $user->setRoles(['ROLE_ADMIN']);
                $user->setCapitan(0);
                $user->setPassword($passwordEncoder->encodePassword($user,$form['password']->getData()));
                $this->em->persist($user);
                $this->em->flush();
                $this->addFlash('exito','Se ha registrado exitósamente el nuevo admin');
            }
           
        }

        return $this->render('login/registeradmin.html.twig',[
            'controller_name' => 'ReservaController',
            'email'=>$this->nav->getDataNav()['email'],
            'admin'=>$this->nav->getDataNav()['admin'],
            'controller_name' => 'LoginController',
            'formulario'=> $form->createView()
        ]);
    }

   



}
