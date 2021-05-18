<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\ResetPassType;
use App\Repository\LoginRepository;
use Respect\Validation\Validator as v;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Login;

class AccountController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        
    }

    public function DeleteAccount(TokenStorageInterface $tokenStorage): Response
    {
        if($this->getUser()){

            $em = $this->getDoctrine()->getManager();
            $login = $this->getUser();
            $tokenStorage->setToken(null);
            $em->remove($login);
            $em->flush();

            $this->addFlash('success', 'Votre compte a été supprimé avec succès.');
            return $this->render('default/home.html.twig');
        }
        return $this->redirectToRoute('app_logout');
    }

    public function Account(): Response
    {
        if($this->getUser()){

            $em = $this->getDoctrine()->getManager();
            $login = $this->getUser();
            $status = $this->getUser()->getStatus();

            $errors = [];

        if(!empty($_POST)){

            $safe = array_map('trim', array_map('strip_tags', $_POST));

            if(!empty($safe['email'])){
                    if(!v::notEmpty()->email()->validate($safe['email'])){ // Validation email
                        $errors[] = 'Votre email est invalide';
                    }
                }
            if(!empty($safe['password'])){
                if(!v::notEmpty()->length(8,20)->validate($safe['password'])){
                        $errors[] = 'Votre mot de passe doit comporter entre 8 et 20 caractères';
                    }
                }
            if(!empty($safe['password'])){
                if(!v::equals($safe['password'])->validate($safe['confirmPassword'])){
                        $errors[] = 'Vos mots de passe ne correspondent pas';
                    }
                }
                

            if(count($errors) === 0){
                if(!empty($safe['email'])){
                        $login->setEmail($safe['email']);
                }
                if(!empty($safe['password'])){
                    $login->setPassword($this->passwordEncoder->encodePassword(
                        $login,
                        $safe['password']
                    ));
                }

                    $em->persist($login);
                    $em->flush();
                    $_POST = array();
                    $this->addFlash('success', 'Compte modifié avec succès');
                }else {
                    $this->addFlash('danger', implode('<br>', $errors));
                }
             }

        return $this->render('admin/content/account/account.html.twig', [
            'controller_name' => 'AccountController',
            'login' => $login,
            'status'=> $status,
        ]);
        }else{
            return $this->redirectToRoute('home_controller');
        }
    }
}
