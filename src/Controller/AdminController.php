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
use App\Entity\Memo;


class AdminController extends AbstractController
{

    public function Admin(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $em = $this->getDoctrine()->getManager();


        $organization = $login->getOrganization()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['organization' => $organization]);
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $organizationMemo = $this->getDoctrine()->getRepository(User::class)->findBy(['organization' => $organization]);
        $memo = $this->getDoctrine()->getRepository(Memo::class)->findBy(['users' => $organizationMemo]);
        $memos = $this->getDoctrine()->getRepository(Memo::class)->findAll();
        
        $directory = $this->getDoctrine()->getRepository(Directory::class)->findAll();
        $organizations = $this->getDoctrine()->getRepository(Organization::class)->findAll();

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'user' => $user,
            'users' =>$users,
            'memo' => $memo,
            'memos' =>$memos,
            'directory' => $directory,
            'organizations' => $organizations,
        ]);
    }

    // ADMIN ORGANIZATION
    public function AdminOrganization(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $organization = $this->getDoctrine()->getRepository(Organization::class)->findAll();

        $user = $this->getDoctrine()->getRepository(User::class);
        // dd($user);
        $errors = [];
        
        if(!empty($_POST['importcsv'])){
            $safe= array_map('trim', array_map('strip_tags', $_POST));

            $em = $this->getDoctrine()->getManager();

            dd($_POST);

            // Verification CSV
            if(isset($_FILES['importcsv']) && $_FILES['importcsv']['error'] != UPLOAD_ERR_NO_FILE){

                if($_FILES['importcsv']['error'] != UPLOAD_ERR_OK){
                    $errors[] = 'Une erreur est survenue lors du transfert du fichier csv'; 
                }
                else {
                   
                    $maxSize = 3 * 10000 * 10000; 

                    if($_FILES['importcsv']['size'] > $maxSize){
                        $errors[] = 'L\'image est trop volumineuse, maximum 300Mo';
                    }
                    else {
                        $allowMimesTypes = ['text/csv',
                        'text/plain',
                        'application/csv',
                        'text/comma-separated-values',
                        'application/excel',
                        'application/vnd.ms-excel',
                        'application/vnd.msexcel',
                        'text/anytext',
                        'application/octet-stream',
                        'application/txt'];

                        if(!in_array($_FILES['importcsv']['type'], $allowMimesTypes)){
                            $errors[] = 'Le type de fichier est invalide';
                        }
                    }
                }
                
            }

            if(count($errors) === 0){   

            if($_FILES['importcsv']['error'] === UPLOAD_ERR_OK){

            $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
            $publicOutput = 'asset/uploads/csv/'; // Chemin à partir de public

            $dirOutput = $rootPublic.$publicOutput;

            switch ($_FILES['.csv']['type']) {
                case 'text/csv':
                case 'application/csv':
                    $extension = 'csv';
                break;

                case 'application/excel':
                case 'application/vnd.ms-excel':
                case 'application/vnd.msexcel';
                case 'application/octet-stream';
                    $extension = 'xls';                  
                break;
                
                case 'application/excel':
                case 'application/vnd.ms-excel':
                case 'application/vnd.msexcel';
                case 'application/octet-stream';
                    $extension = 'xlsx';
                break;
            }

            $filename = uniqid().'.'.$extension;
            
            if(!move_uploaded_file($_FILES['importcsv']['tmp_name'], $dirOutput.$filename)){
                die('Erreur d\'upload fichier Images');
            }
        }

        $reader = Reader::createFromPath('%kernel.root.dir%/../public/asset/uploads/csv'.$fileName);
        $result = $reader->fetchAssoc();

            foreach ($result as $row){

                $user = new User();

                $user->setFirstname($row['prenom']);
                $user->setLastname($row['nom']);
                $user->setProfession($row['profession']);
                $user->setMobilenumber($row['mobile']);
                $user->setPhonenumber($row['telephone']);
                $user->setStructure($row['batiment']);
                $user->setFloor($row['etage']);
                $user->setRole($row['role']);
                $user->setGrade($row['grade']);
                $user->setDirectory($row['annuaire']);
                $user->setOrganization($row['organisme']);

                $this->em->persist($user);
            }
            
            
            $this->em->flush();

            $this->addFlash('success', 'L\'ajout des contacts via le fichier CSV a été réalisé avec succès.');

            $this->redirectToRoute('admin_organization_controller');

        }else{

            $this->addFlash('danger',  implode('<br>', $errors));
        }
    }

        return $this->render('admin/content/organization/adminOrganization.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'organization'=> $organization,
        ]);
    }

    public function AddAdminOrganization(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $em = $this->getDoctrine()->getManager();

        $organization = $this->getDoctrine()->getRepository(Organization::class)->findAll();

        $errors = [];
    
        if(!empty($_POST)){

            $login = $this->getUser();
            $status = $this->getUser()->getStatus();
            $safe = array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();
            $organization = $this->getDoctrine()->getRepository(Organization::class)->findAll();

            if(!v::length(2, 50)->validate($safe['name'])){
                $errors[]= 'Le nom de l\'organisme doit contenir entre 2 et 50 caractères';
            }

            if(!v::length(6)->validate($safe['coded'])){
                $errors[]= 'Le code de l\'organisme doit contenir exactement 6 chiffres';
            }
        

        if(count($errors) === 0){

            $organization = new Organization;

            $organization->setName($safe['name']);
            $organization->setCoded($safe['coded']);

                // Je prepare la sauvegarde en base de donnée
            $em->persist($organization);

            // L'équivalent du execute()
            $em->flush();

            //  On crée le message Flash
            $this->addFlash('success',  'L\'organisme a bien été ajouté.');

            return $this->redirectToRoute('admin_organization_controller');

        }else{
            
            $this->addFlash('danger',  implode('<br>', $errors));
            }

        }
        return $this->render('admin/content/organization/addAdminOrganization.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'organization'=> $organization,
        ]);
    }

    public function UpdateAdminOrganization(Request $request): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $em = $this->getDoctrine()->getManager();

        $organization= $em->getRepository(Organization::class)->findOneBy(['id'=> $_GET['organization']]);

        $errors = [];
    
        if(!empty($_POST)){

            $login = $this->getUser();
            $status = $this->getUser()->getStatus();
            $safe = array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();

            if(!v::length(2, 50)->validate($safe['name'])){
                $errors[]= 'Le nom de l\'organisme doit contenir entre 2 et 50 caractères';
            }

            if(!v::length(6)->validate($safe['coded'])){
                $errors[]= 'Le code de l\'organisme doit contenir exactement 6 chiffres';
            }
        

        if(count($errors) === 0){

            $organization->setName($safe['name']);
            $organization->setCoded($safe['coded']);

        if($request->isMethod('POST'))

            $login = $this->getUser();
            $status = $this->getUser()->getStatus();
            
            $em = $this->getDoctrine()->getManager();
                // Je prepare la sauvegarde en base de donnée
            $em->persist($organization);

            // L'équivalent du execute()
            $em->flush();

            //  On crée le message Flash
            $this->addFlash('success',  'L\'organisme a bien été modifié.');

            return $this->redirectToRoute('admin_organization_controller');

        }else{
            
            $this->addFlash('danger',  implode('<br>', $errors));
            }

        }
        return $this->render('admin/content/organization/updateAdminOrganization.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'organization'=> $organization,
        ]);
    }

    public function DeleteAdminOrganization(): Response
    {

        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        $em = $this->getDoctrine()->getManager();
        $organization= $em->getRepository(Organization::class)->findOneBy(['id'=> $_GET['organization']]);

        $em->remove($organization);

        $em->flush();

        $this->addFlash('success',  'L\'organisme a bien été supprimé.');

        return $this->redirectToRoute('admin_organization_controller');
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

    // SITES/AGENCES
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

    public function AddAdminAgency(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        return $this->render('admin/content/agency/addAdminAgency.html.twig', [
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
    
        if(!empty($_POST)){

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
