<?php

namespace App\Controller;

use App\Entity\Login;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/Accueil", name="Accueil")
     */
    public function Home(): Response
    {
        
        return $this->render('default/home.html.twig', [
            'home_controller' => 'DefaultController',
        ]);
    }
    
    public function HomeConnexion():Response{

        $login = $this->getUser();
        $em = $this->getDoctrine()->getManager();


        $status = $this->getUser()->getStatus();
        
        if($this->getUser() && $status === "SNPCA" || $status === "RPCA" || $status === "Utilisateur" ){
            
            $login = $this->getUser();
            $em = $this->getDoctrine()->getManager();
    

            $status = $this->getUser()->getStatus();

            return $this->render('default/homeConnexion.html.twig', [
                'home_connexion_controller' => 'DefaultController',
                'status' => $status,
                'page' => 'accueil',
                'login' => $login,
            ]);

        }else{

            return $this->render('default/home.html.twig', [
                'home_controller' => 'DefaultController',
            ]);
        }
    }



}

