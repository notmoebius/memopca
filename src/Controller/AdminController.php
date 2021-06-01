<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Organization;
use App\Entity\Login;
use App\Entity\User;
use App\Entity\Directory;
use App\Entity\Memo;
use App\Entity\Agency;
use App\Entity\CrisisRoom;
use App\Entity\TypeCrisisRoom;
use League\Csv\Reader;
use League\Csv\Writer;


class AdminController extends AbstractController
{

    // 
    // 
    // ACCUEIL
    // 
    // 

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


    // 
    // 
    // ORGANISATION
    // 
    // 


    public function AdminOrganization(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $organization = $this->getDoctrine()->getRepository(Organization::class)->findAll();


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
            if(!v::length(2, 3)->validate($safe['department'])){
                $errors[]= 'Le numéro de département doit contenir entre 2 et 3caractères';
            }

            if(!v::length(6)->validate($safe['coded'])){
                $errors[]= 'Le code de l\'organisme doit contenir exactement 6 chiffres';
            }
        

        if(count($errors) === 0){

            $organization = new Organization;

            $organization->setName($safe['name']);
            $organization->setDepartment($safe['department']);
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
            if(!v::length(2, 3)->validate($safe['department'])){
                $errors[]= 'Le numéro de département doit contenir entre 2 et 3caractères';
            }

            if(!v::length(6)->validate($safe['coded'])){
                $errors[]= 'Le code de l\'organisme doit contenir exactement 6 chiffres';
            }
        

        if(count($errors) === 0){

            $organization->setName($safe['name']);
            $organization->setDepartment($safe['department']);
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
    
    public function ImportAdminOrganization(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $organization = $this->getDoctrine()->getRepository(Organization::class)->findAll();

        $user = $this->getDoctrine()->getRepository(User::class);
        // dd($user);
        $errors = [];
        
        if(!empty($_POST)){
            $safe= array_map('trim', array_map('strip_tags', $_POST));

            $em = $this->getDoctrine()->getManager();

        

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

            if($_FILES['importcsv']['error'] === UPLOAD_ERR_OK && !empty($_FILES['importcsv'])){

            $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
            $publicOutput = 'asset/uploads/csv/'; // Chemin à partir de public

            $dirOutput = $rootPublic.$publicOutput;

            switch ($_FILES['importcsv']['type']) {
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

        $reader = Reader::createFromPath('asset/uploads/csv/'.$filename);
        

            foreach ($reader->fecthAssoc(0) as $row){
                $writer = Writer::createFromPath('asset/uploads/csv/'.$filename);

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

                $this->$em->persist($user);

                dd($user);
            }
            
            
            $em->flush();

            $this->addFlash('success', 'L\'ajout des contacts via le fichier CSV a été réalisé avec succès.');

            $this->redirectToRoute('admin_organization_controller');

        }else{

            $this->addFlash('danger',  implode('<br>', $errors));
        }
    }

        return $this->render('admin/content/organization/importAdminOrganization.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'organization'=> $organization,
        ]);
    }


    // 
    // 
    // SALLES DE CRISES
    // 
    // 


    public function AdminCrisisRoom(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $crisisRoom = $this->getDoctrine()->getRepository(CrisisRoom::class)->findAll();

        return $this->render('admin/content/crisis/adminCrisis.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'crisis' => $crisisRoom,
        ]);
    }

    public function AddAdminCrisisRoom(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $crisisRoom = $this->getDoctrine()->getRepository(CrisisRoom::class)->findAll();
        
        $errors = [];
        if(!empty($_POST)){

            $safe = array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();
            $organization = $em ->getRepository(Organization::Class);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            $status = $login->getStatus();

            if(!v::length(2, 50)->validate($safe['reference'])){
                $errors[]= 'Le libellée du site doit contenir entre 2 et 50 caracteres';
            }

            if(!isset($safe['phonenumber'])){
                if(!v::length(10)->validate($safe['phonenumber'])){
                    $errors[]= 'le numéro de téléphone doit contenir exactement 10 chiffres';
                }
                if(!v::length(10)->validate($safe['faxnumber'])){
                    $errors[]= 'le numéro de téléphone doit contenir exactement 10 chiffres';
                }
            }

            if(!isset($safe['typecrisis'])){ // Validation grade
                $errors[] = 'Vous devez choisir si cette salle de crise est "Interne" ou "Externe".';
            }

            if(!v::length(2, 50)->validate($safe['address1'])){
                $errors[]= 'L\'adresse doit contenir entre 2 et 50 caracteres';
            }

            if(!v::length(5, 30)->validate($safe['address2'])){
                $errors[]= 'L\'adresse doit contenir entre 5 et 30 caracteres';
            }

            if(!v::length(0, 20)->validate($safe['address3'])){
                $errors[]= 'L\'adresse doit contenir entre 0 et 20 caracteres';
            }

            if(!isset($safe['organization'])){ // Validation organisation
                $errors[] = 'Vous devez choisir l\'organisme auquel le site est rattaché.';
            }


            if(isset($_FILES['planCrisisRoom']) && $_FILES['planCrisisRoom']['error'] != UPLOAD_ERR_NO_FILE){

            if($_FILES['planCrisisRoom']['error'] != UPLOAD_ERR_OK){

                $errors[] = 'Une erreur est survenue lors du transfert du plan'; 

            }else{

                $maxSize = 3 * 1000 * 1000; 

            if($_FILES['planCrisisRoom']['size'] > $maxSize){

                $errors[] = 'Le plan est trop volumineus, maximum 3Mo';

            }else{

                $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'application/pdf'];

            if(!in_array($_FILES['planCrisisRoom']['type'], $allowMimesTypes)){

                $errors[] = 'Le type de fichier est invalide';

                }
            }
        }
    }
        if(count($errors) === 0){

            $login = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $organization = $em ->getRepository(Organization::Class);
            $typecrisis = $em ->getRepository(TypeCrisisRoom::Class);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            $safe['typecrisis'] = $typecrisis->FindOneBy(['id' => $safe['typecrisis']]);

            $crisisRoom = new CrisisRoom;

            $crisisRoom->setReference($safe['reference']);
            $crisisRoom->setTypeCrisisRoom($safe['typecrisis']);
            $crisisRoom->setPhonenumber($safe['phonenumber']);
            $crisisRoom->setFaxnumber($safe['faxnumber']);
            $crisisRoom->setAddress1($safe['address1']);
            $crisisRoom->setAddress2($safe['address2']);
            $crisisRoom->setAddress3($safe['address3']);
            // PHOTO
            if($_FILES['planCrisisRoom']['error'] === UPLOAD_ERR_OK && !empty($_FILES['planCrisisRoom'])){

                $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                $publicOutput = 'Documents_Locaux/Batiments/Plans/'; // Chemin à partir de public
                $dirOutput = $rootPublic.$publicOutput;

                
                switch ($_FILES['planCrisisRoom']['type']) {
                    case 'image/jpg':
                    case 'image/jpeg':
                    case 'image/pjpeg':
                        $extension = 'jpg';
                    break;

                    case 'image/png':
                        $extension = 'png';
                    break;

                    case 'application/pdf':
                        $extension = 'pdf';
                    break;                
                }
                

                $filename = basename($_FILES['planCrisisRoom']['name']);
        
            if(!move_uploaded_file($_FILES['planCrisisRoom']['tmp_name'], $dirOutput.$filename)){
                die('Erreur d\'upload fichier Plan');
            }
        
            $crisisRoom->setPlan($publicOutput.$filename);
        }
            $crisisRoom->setOrganization($safe['organization']);

            $em->persist($crisisRoom);
            $em->flush();
            $_POST = array();

            $this->addFlash('success',  'La salle de crise a été créée avec succès.');
                
            return $this->redirectToRoute('admin_crisis_controller');
                
            } // endif count($errors) === 0
            else {
                $this->addFlash('danger', implode('<br>', $errors));
            }

        }
        return $this->render('admin/content/crisis/addAdminCrisis.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'crisis' => $crisisRoom
        ]);
    }

    public function UpdateAdminCrisisRoom(Request $request): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $crisisRoom = $this->getDoctrine()->getRepository(CrisisRoom::class)->findOneBy(['id'=> $_GET['crisis']]);
        
        $errors = [];
        if(!empty($_POST)){

            $safe = array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();
            $organization = $em ->getRepository(Organization::Class);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            $safe['typecrisis'] = $organization->FindOneBy(['id' => $safe['typecrisis']]);

            $status = $login->getStatus();

            if(!v::length(2, 50)->validate($safe['reference'])){
                $errors[]= 'Le libellée du site doit contenir entre 2 et 50 caracteres';
            }

            if(!isset($safe['phonenumber'])){
                if(!v::length(10)->validate($safe['phonenumber'])){
                    $errors[]= 'le numéro de téléphone doit contenir exactement 10 chiffres';
                }
                if(!v::length(10)->validate($safe['faxnumber'])){
                    $errors[]= 'le numéro de téléphone doit contenir exactement 10 chiffres';
                }
            }

            if(!isset($safe['typecrisis'])){ // Validation grade
                $errors[] = 'Vous devez choisir si cette salle de crise est "Interne" ou "Externe".';
            }

            if(!v::length(2, 50)->validate($safe['address1'])){
                $errors[]= 'L\'adresse doit contenir entre 2 et 50 caracteres';
            }

            if(!v::length(5, 30)->validate($safe['address2'])){
                $errors[]= 'L\'adresse doit contenir entre 5 et 30 caracteres';
            }

            if(!v::length(0, 20)->validate($safe['address3'])){
                $errors[]= 'L\'adresse doit contenir entre 0 et 20 caracteres';
            }

            if(!isset($safe['organization'])){ // Validation organisation
                $errors[] = 'Vous devez choisir l\'organisme auquel le site est rattaché.';
            }


            if(isset($_FILES['planCrisisRoom']) && $_FILES['planCrisisRoom']['error'] != UPLOAD_ERR_NO_FILE){

            if($_FILES['planCrisisRoom']['error'] != UPLOAD_ERR_OK){

                $errors[] = 'Une erreur est survenue lors du transfert du plan'; 

            }else{

                $maxSize = 3 * 1000 * 1000; 

            if($_FILES['planCrisisRoom']['size'] > $maxSize){

                $errors[] = 'Le plan est trop volumineus, maximum 3Mo';

            }else{

                $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'application/pdf'];

            if(!in_array($_FILES['planCrisisRoom']['type'], $allowMimesTypes)){

                $errors[] = 'Le type de fichier est invalide';

                }
            }
        }
    }
        if(count($errors) === 0){

            $login = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $organization = $em ->getRepository(Organization::Class);
            $typecrisis = $em ->getRepository(TypeCrisisRoom::Class);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            $safe['typecrisis'] = $typecrisis->FindOneBy(['id' => $safe['typecrisis']]);



            $crisisRoom->setReference($safe['reference']);
            $crisisRoom->setTypeCrisisRoom($safe['typecrisis']);
            $crisisRoom->setPhonenumber($safe['phonenumber']);
            $crisisRoom->setFaxnumber($safe['faxnumber']);
            $crisisRoom->setAddress1($safe['address1']);
            $crisisRoom->setAddress2($safe['address2']);
            $crisisRoom->setAddress3($safe['address3']);
            // PHOTO
            if($_FILES['planCrisisRoom']['error'] === UPLOAD_ERR_OK && !empty($_FILES['planCrisisRoom'])){

                $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                $publicOutput = 'Documents_Locaux/Batiments/Plans/'; // Chemin à partir de public
                $dirOutput = $rootPublic.$publicOutput;

                
                switch ($_FILES['planCrisisRoom']['type']) {
                    case 'image/jpg':
                    case 'image/jpeg':
                    case 'image/pjpeg':
                        $extension = 'jpg';
                    break;

                    case 'image/png':
                        $extension = 'png';
                    break;

                    case 'application/pdf':
                        $extension = 'pdf';
                    break;                
                }
                

                $filename = basename($_FILES['planCrisisRoom']['name']);
        
            if(!move_uploaded_file($_FILES['planCrisisRoom']['tmp_name'], $dirOutput.$filename)){
                die('Erreur d\'upload fichier Plan');
            }
        
            $crisisRoom->setPlan($publicOutput.$filename);
        }
            $crisisRoom->setOrganization($safe['organization']);

            if($request->isMethod('POST'))

            $em->persist($crisisRoom);
            $em->flush();
            $_POST = array();

            $this->addFlash('success',  'La salle de crise a été modifiée avec succès.');
                
            return $this->redirectToRoute('admin_crisis_controller');
                
            } // endif count($errors) === 0
            else {
                $this->addFlash('danger', implode('<br>', $errors));
            }

        }
        return $this->render('admin/content/crisis/updateAdminCrisis.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'crisis' => $crisisRoom,
        ]);
    }

    public function DeleteAdminCrisisRoom(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $em = $this->getDoctrine()->getManager();
        
        $crisisRoom = $this->getDoctrine()->getRepository(CrisisRoom::class)->findOneBy(['id'=> $_GET['crisis']]);

        $em->remove($crisisRoom);

        $em->flush();

        $this->addFlash('success',  'La salle de crise a bien été supprimée.');

        return $this->redirectToRoute('admin_crisis_controller');

    }

    public function JsonCrisisRoom(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $em = $this->getDoctrine()->getManager();
        $crisisRoom = $this->getDoctrine()->getRepository(CrisisRoom::class)->findAll();

        $data = array();

        foreach ($crisisRoom as $key => $crisisRoom){
            $data[$key]['ref'] = $crisisRoom->getReference();
            $data[$key]['type'] = $crisisRoom->getTypeCrisisRoom()->getName();
            $data[$key]['tel'] = $crisisRoom->getPhonenumber();
            $data[$key]['fax'] = $crisisRoom->getFaxnumber();
            $data[$key]['addresse1'] = $crisisRoom->getAddress1();
            $data[$key]['addresse2'] = $crisisRoom->getAddress2();
            $data[$key]['addresse3'] = $crisisRoom->getAddress3();
            $data[$key]['plan'] = $crisisRoom->getPlan();
        }        
        
        $response = new JsonResponse();
        
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $response->setData($data);
        return $response;

    }

    // 
    // 
    // SITES/AGENCES
    // 
    // 


    public function AdminAgency(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $agency = $this->getDoctrine()->getRepository(Agency::class)->findAll();

        return $this->render('admin/content/agency/adminAgency.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'agency' =>$agency,
        ]);
    }

    public function AddAdminAgency(): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $agency = $this->getDoctrine()->getRepository(Agency::class)->findAll();

        $errors = [];
        if(!empty($_POST)){

            $safe = array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();
            $organization = $em ->getRepository(Organization::Class);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            $status = $login->getStatus();
            
            if(!v::length(2, 50)->validate($safe['site'])){
                $errors[]= 'Le libellée du site doit contenir entre 2 et 50 caracteres';
            }


            if(!isset($safe['organization'])){ // Validation organisation
                $errors[] = 'Vous devez choisir l\'organisme auquel le site est rattaché.';
            }


            if(isset($_FILES['planAgency']) && $_FILES['planAgency']['error'] != UPLOAD_ERR_NO_FILE){

            if($_FILES['planAgency']['error'] != UPLOAD_ERR_OK){

                $errors[] = 'Une erreur est survenue lors du transfert du plan'; 

            }else{

                $maxSize = 3 * 1000 * 1000; 

            if($_FILES['planAgency']['size'] > $maxSize){

                $errors[] = 'Le plan est trop volumineus, maximum 3Mo';

            }else{

                $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'application/pdf'];

            if(!in_array($_FILES['planAgency']['type'], $allowMimesTypes)){

                $errors[] = 'Le type de fichier est invalide';

                }
            }
        }
    }
        if(count($errors) === 0){

            $login = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $organization = $em ->getRepository(Organization::Class);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            

            $agency = New Agency;

            $agency->setName($safe['site']);

                // PHOTO
                if($_FILES['planAgency']['error'] === UPLOAD_ERR_OK && !empty($_FILES['planAgency'])){

                    $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                    $publicOutput = 'Documents_Locaux/Batiments/Plans/'; // Chemin à partir de public
                    $dirOutput = $rootPublic.$publicOutput;

                    
                    switch ($_FILES['planAgency']['type']) {
                        case 'image/jpg':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $extension = 'jpg';
                        break;

                        case 'image/png':
                            $extension = 'png';
                        break;

                        case 'application/pdf':
                            $extension = 'pdf';
                        break;                
                    }
                    

                    $filename = basename($_FILES['planAgency']['name']);
            
                if(!move_uploaded_file($_FILES['planAgency']['tmp_name'], $dirOutput.$filename)){
                    die('Erreur d\'upload fichier Plan');
                }
            
                $agency->setPlan($publicOutput.$filename);
            }

            $agency->setOrganization($safe['organization']); 

            $em->persist($agency);
            $em->flush();
            $_POST = array();

            $this->addFlash('success',  'La site a été créé avec succès.');
                
            return $this->redirectToRoute('admin_agency_controller');
                
            } // endif count($errors) === 0
            else {
                $this->addFlash('danger', implode('<br>', $errors));
            }

        }
        return $this->render('admin/content/agency/addAdminAgency.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'agency' =>$agency,
        ]);
    }

    public function UpdateAdminAgency(Request $request): Response
    {
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $em = $this->getDoctrine()->getManager();
        $agency= $em->getRepository(Agency::class)->findOneBy(['id'=> $_GET['agency']]);

        $errors = [];
        if(!empty($_POST)){

            $safe = array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();

            $status = $login->getStatus();
            
            if(!v::length(2, 50)->validate($safe['site'])){
                $errors[]= 'Le libellé doit contenir entre 2 et 50 caracteres';
            }


            if(!isset($safe['organization'])){ // Validation organisation
                $errors[] = 'Vous devez choisir l\'organisme auquel le site est rattaché.';
            }


            if(isset($_FILES['planAgency']) && $_FILES['planAgency']['error'] != UPLOAD_ERR_NO_FILE){

            if($_FILES['planAgency']['error'] != UPLOAD_ERR_OK){

                $errors[] = 'Une erreur est survenue lors du transfert du plan'; 

            }else{

                $maxSize = 3 * 1000 * 1000; 

            if($_FILES['planAgency']['size'] > $maxSize){

                $errors[] = 'Le plan est trop volumineus, maximum 3Mo';

            }else{

                $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'application/pdf'];

            if(!in_array($_FILES['planAgency']['type'], $allowMimesTypes)){

                $errors[] = 'Le type de fichier est invalide';

                }
            }
        }
    }
        if(count($errors) === 0){

            $login = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $organization = $em ->getRepository(Organization::Class);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            



            $agency->setName($safe['site']);

                // PHOTO
                if($_FILES['planAgency']['error'] === UPLOAD_ERR_OK && !empty($_FILES['planAgency'])){

                    $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                    $publicOutput = 'Documents_Locaux/Batiments/Plans/'; // Chemin à partir de public
                    $dirOutput = $rootPublic.$publicOutput;

                    
                    switch ($_FILES['planAgency']['type']) {
                        case 'image/jpg':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $extension = 'jpg';
                        break;

                        case 'image/png':
                            $extension = 'png';
                        break;

                        case 'application/pdf':
                            $extension = 'pdf';
                        break;                
                    }
                    

                    $filename = basename($_FILES['planAgency']['name']);
            
                if(!move_uploaded_file($_FILES['planAgency']['tmp_name'], $dirOutput.$filename)){
                    die('Erreur d\'upload fichier Plan');
                }
            
                $agency->setPlan($publicOutput.$filename);
            }

            $agency->setOrganization($safe['organization']); 

            if($request->isMethod('POST'))

            $em->persist($agency);
            $em->flush();
            $_POST = array();

            $this->addFlash('success',  'La site a été modifié avec succès.');
                
            return $this->redirectToRoute('admin_agency_controller');
                
            } // endif count($errors) === 0
            else {
                $this->addFlash('danger', implode('<br>', $errors));
            }

        }
        return $this->render('admin/content/agency/updateAdminAgency.html.twig', [
            'controller_name' => 'AdminController',
            'login' => $login,
            'status' =>$status,
            'agency' =>$agency,
        ]);
    }
        
    public function DeleteAdminAgency(): Response
    {

        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository(Agency::class)->findOneBy(['id'=> $_GET['agency']]);

        $em->remove($agency);

        $em->flush();

        $this->addFlash('success',  'Le site a bien été supprimé.');

        return $this->redirectToRoute('admin_agency_controller');
    }

    public function JsonAgency(): Response
    {
        
        // $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $agency = $this->getDoctrine()->getRepository(Agency::class)->findAll();

        $data = array();

        foreach ($agency as $key => $agency){
            $data[$key]['sites'] = array($agency->getName(),
            $agency->getPlan());
        }        
        
        $response = new JsonResponse();
        
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $response->setData($data);
        return $response;

    }

    // public function JsonAgency1(): Response
    // {
        
    //     // $user = $this->getUser();
    //     $em = $this->getDoctrine()->getManager();
    //     $agency = $this->getDoctrine()->getRepository(Agency::class)->findAll();

    //     $data = array();
        
    //     foreach ($agency as $key => $agency){
    //         $data[$key]['id'] = "id".$user->getName();
    //         $data[$key]['fonction'] = $user->getPlan();
    //     }        
        
    //     $response = new JsonResponse();
        
    //     $response->headers->set('Content-Type', 'application/json');
    //     $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response->setData($data);
    //     return $response;

    // }


    // 
    // 
    // PROCESSUS
    // 
    // 


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


    // 
    // 
    // TO DO LIST
    // 
    // 


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


    // 
    // 
    // BASES DOCUMENTAIRE
    // 
    // 


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


    // 
    // 
    // PROFIL ADMIN
    // 
    // 


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
