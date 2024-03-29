<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\MakerBundle\Validator;
use Respect\Validation\Validator as v;
use Cnam\ValidatorBundle\Constraints\Diademe\Diademe;
use Symfony\Component\Validator\Validation;
use Cnam\ValidatorBundle\Constraints as CnamAssert;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Organization;
use App\Entity\Login;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\Session\Session;


class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function Register(LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler, Request $request): Response
    {
        $session =$request->getSession();

        if(!isset($session)){
            $session = new Session();
            $session->start();
        }
        // Set and Get session attributes
        if(!empty($_GET['status'])){
            $session->set('status', $_GET['status']);
        }else{
            return $this->redirectToRoute('home_controller');
        }

        // User connecté, redirection vers l'accueil
        if($this->getUser()){
            return $this->redirectToRoute('home_controller');
        }else{
            
            
            $errors = [];
    
            if(!empty($_POST['email'])){

                $safe = array_map('trim', array_map('strip_tags', $_POST));
                $em = $this->getDoctrine()->getManager();
                $organization = $em ->getRepository(Organization::Class);
                $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
                $status = $_GET['status'];
                
                if(!v::length(2, 50)->validate($safe['firstname'])){
                    $errors[]= 'Votre prénom doit contenir entre 2 et 50 caracteres';
                }
    
                if(!v::length(2, 50)->validate($safe['lastname'])){
                    $errors[]= 'Votre nom doit contenir entre 2 et 50 caracteres';
                }

                if(!v::notEmpty()->email()->validate($safe['email'])){ // Validation email
                    $errors[] = 'Votre email est invalide';
                }
    
                if(!v::length(6,20)->validate($safe['password'])){ // validation mot de passe
                    $errors[] = 'Votre mot de passe doit comporter entre 6 et 20 caractères';
                }
                if(!v::equals($safe['password'])->validate($safe['confirmPassword'])){
                    $errors[] = 'Vos mots de passe ne correspondent pas';
                }

                if(!isset($safe['organization'])){ // Validation organisation
                    $errors[] = 'Vous devez choisir votre organisme.';
                }

                if(isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE){

                if($_FILES['photo']['error'] != UPLOAD_ERR_OK){

                    $errors[] = 'Une erreur est survenue lors du transfert de l\'image'; 

                }else{
    
                    $maxSize = 3 * 1000 * 1000; 
    
                if($_FILES['photo']['size'] > $maxSize){

                    $errors[] = 'L\'image est trop volumineuse, maximum 3Mo';

                }else{
    
                    $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png'];

                if(!in_array($_FILES['photo']['type'], $allowMimesTypes)){

                    $errors[] = 'Le type de fichier est invalide';

                    }
                }
            }
        } // endif isset($_FILES['profilPicture']) && !empty($_FILES['profilPicture']) && $_FILES['profilPicture']['error'] != UPLOAD_ERR_NO_FILE
          
        if(count($errors) === 0){
    
                    $em = $this->getDoctrine()->getManager();
                    $organization = $em ->getRepository(Organization::Class);
                    $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);

                    $login = new Login;

                    $login->setFirstname($safe['firstname']);
                    $login->setLastname($safe['lastname']);
                    $login->setEmail($safe['email']);
                    $login->setStatus($status);
                    $login->setOrganization($safe['organization']);
                    $login->setPassword($this->passwordEncoder->encodePassword(
                        $login,
                        $safe['password']
                    ));
                     // PHOTO
                    if($_FILES['photo']['error'] === UPLOAD_ERR_OK){
                        $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                        $publicOutput = 'asset/uploads/admin/'; // Chemin à partir de public
                        $dirOutput = $rootPublic.$publicOutput;
    
                        switch ($_FILES['photo']['type']) {
                            case 'image/jpg':
                            case 'image/jpeg':
                            case 'image/pjpeg':
                                $extension = 'jpg';
                            break;
    
                            case 'image/png':
                                $extension = 'png';
                            break;
    
                        }
    
                        $filename = uniqid().'.'.$extension;
                
                if(!move_uploaded_file($_FILES['photo']['tmp_name'], $dirOutput.$filename)){
                    die('Erreur d\'upload fichier Images');
                }

                $login->setPhoto($publicOutput.$filename);
            }
                    $em->persist($login);
                    $em->flush();
                    $_POST = array();

                    $this->addFlash('success',  'Votre inscription est terminée. Vous êtes maintenant connecté.');

                    return $guardHandler->authenticateUserAndHandleSuccess(
                        $login,          // the User object you just created
                        $request,
                        $authenticator, // authenticator whose onAuthenticationSuccess you want to use
                        'main'          // the name of your firewall in security.yaml
                    );
                } // endif count($errors) === 0
                else {
                    $this->addFlash('danger', implode('<br>', $errors));
                }
    
            }
        }

        return $this->render('user/register.html.twig', [
            'user_register' => 'UserController',
        ]);
    }
}
