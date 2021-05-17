<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;
use App\Entity\Organization;
use App\Entity\Login;
use App\Entity\User;
use App\Entity\Directory;


class AdminController extends AbstractController
{

    public function Admin(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        $organization = $login->getOrganization()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['organization' => $organization]);
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $directory = $this->getDoctrine()->getRepository(Directory::class)->findAll();
        $organizations = $this->getDoctrine()->getRepository(Organization::class)->findAll();

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'user' => $user,
            'users' =>$users,
            'directory' => $directory,
            'organizations' => $organizations,
        ]);
    }

    public function AdminOrganization(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        return $this->render('admin/content/organization/adminOrganization.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
        ]);
    }

    public function AdminCrisis(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        return $this->render('admin/content/crisis/adminCrisis.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
        ]);
    }

    public function AdminAgency(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        return $this->render('admin/content/agency/adminAgency.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
        ]);
    }

    public function AdminProcess(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        return $this->render('admin/content/process/adminProcess.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
        ]);
    }

    public function AdminList(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        return $this->render('admin/content/todolist/adminList.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
        ]);
    }

    public function AdminDocuments(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        return $this->render('admin/content/documents/adminDocuments.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
        ]);
    }

    public function ProfilAdmin(Request $request): Response
    {
        $login = $this->getUser();
        // dd($login);

        $errors = [];
    
        if(!empty($_POST['email'])){

            $safe = array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();
            $organization = $em ->getRepository(Organization::Class);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            $status = $login->getStatus();
            
            if(!v::length(2, 50)->validate($safe['firstname'])){
                $errors[]= 'Votre prénom doit contenir entre 2 et 50 caracteres';
            }

            if(!v::length(2, 50)->validate($safe['lastname'])){
                $errors[]= 'Votre nom doit contenir entre 2 et 50 caracteres';
            }

            if(!v::notEmpty()->email()->validate($safe['email'])){ // Validation email
                $errors[] = 'Votre email est invalide';
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
                $login = $this->getUser();
                $em = $this->getDoctrine()->getManager();
                $organization = $em ->getRepository(Organization::Class);
                $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);


                $login->setFirstname($safe['firstname']);
                $login->setLastname($safe['lastname']);
                $login->setEmail($safe['email']);
                $login->setStatus($status);
                $login->setOrganization($safe['organization']);
                
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

        if($request->isMethod('POST'))
                $em->persist($login);
                $em->flush();
                $_POST = array();

                $this->addFlash('success',  'Votre profil a été modifié avec succès.');
                
                return $this->render('admin/profilAdmin.html.twig', [
                    'controller_name' => 'AdminController',
                    'login' => $login,
                ]);
                
            } // endif count($errors) === 0
            else {
                $this->addFlash('danger', implode('<br>', $errors));
            }

        }
        
        return $this->render('admin/profilAdmin.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,

        ]);
    }

}
