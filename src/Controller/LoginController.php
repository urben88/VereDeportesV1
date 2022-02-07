<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//! Para el entity manager
use Doctrine\ORM\EntityManagerInterface;


//TODO Forms
use App\Form\UserType;
//TODO Entidades
use App\Entity\User;

//Obtener respuesta
use Symfony\Component\HttpFoundation\Request;

//Encriptar la contraseña
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    /**
     * @Route("/register",name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface  $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form['Repeat_password']->getData() != $form['password']->getData()){
                $this->addFlash('error','Las constraseñas no son iguales');
            }else{
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($passwordEncoder->encodePassword($user,$form['password']->getData()));
                $this->em->persist($user);
                $this->em->flush();
                $this->addFlash('exito','Se ha registrado exitósamente');
            }
           
        }

        return $this->render('login/register.html.twig',[
            'controller_name' => 'LoginController',
            'formulario'=> $form->createView()
        ]);
    }
}
