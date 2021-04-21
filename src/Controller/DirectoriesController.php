<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Login;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Grade;
use App\Entity\Directory;
use App\Entity\Organization;
use Respect\Validation\Validator as v;
use Cnam\ValidatorBundle\Constraints\Diademe\Diademe;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\JsonResponse;
use ZipArchive;


class DirectoriesController extends AbstractController
{

    // LISTING UTILISATEURS DANS L'ANNUAIRE

    public function ListUser(Request $request): Response
    {

        $login = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class);

        $status = $this->getUser()->getStatus();
        // FILTRE PAR ROLE

        if(isset($_GET['page'])){     

            if($_GET['page'] == 'Accueil'){


            // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getDoctrine()->getRepository(User::class)->findAll();

            return $this->render('directories/listUser.html.twig', [
                'user' => $user,
                'status' => $status,
                'login' => $login,
                'page' => 'annuaire',
                'role' => 'allfrance',
                'directory' =>'all'
            ]);
    
            }elseif($_GET['page'] == 'Accueil-Organisme' && $status === 'RPCA'){

                $login = $this->getUser();
                
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $em = $this->getDoctrine()->getManager();
                $organization = $login->getOrganization()->getId();
                

                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['organization' => $organization]);
                

                return $this->render('directories/listUser.html.twig', [
                    'user' => $user,
                    'login'=> $login,
                    'status' => $status,
                    'organization' => $organization,
                    'page' => 'annuaire',
                    'role' => 'allorganisme',
                    'directory' => 'RPCA',
                    ]);
    
                }elseif($_GET['page'] == 'RPCA' ){

                $user = $this->getUser();
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $em = $this->getDoctrine()->getManager();

                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['role' => '1']);
                

                return $this->render('directories/listUser.html.twig', [
                    'user' => $user,
                    'page' => 'annuaire',
                    'status' => $status,
                    'login' => $login,
                    'role' => 'RPCA',
                    'directory' => 'RPCA',
                    ]);
    
                }elseif($_GET['page'] == 'Direction' ){
                
                    $user = $this->getUser();
                    // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                    $em = $this->getDoctrine()->getManager();
                    // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                
                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['role' => '2']);
    
                return $this->render('directories/listUser.html.twig', [
                    'user' => $user,
                    'page' => 'annuaire',
                    'status' => $status,
                    'login' => $login,
                    'role' => 'Direction',
                    'directory' => 'Direction',
                    ]);
    
                }elseif($_GET['page'] == 'Manager' ){
                    
                    $user = $this->getUser();
                    // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                    $em = $this->getDoctrine()->getManager();

                    $user = $this->getDoctrine()->getRepository(User::class)->findBy(['role' => '3']);
    
                    return $this->render('directories/listUser.html.twig', [
                        'user' => $user,
                        'page' => 'annuaire',
                        'status' => $status,
                        'login' => $login,
                        'role' => 'Manager',
                        'directory' => 'Manager',
                        ]);
                }elseif($_GET['page'] == 'autre-rôle' ){
                    
                    $user = $this->getUser();
                    // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                    $em = $this->getDoctrine()->getManager();

                    $user = $this->getDoctrine()->getRepository(User::class)->findBy(['role' => '4']);
    
                    return $this->render('directories/listUser.html.twig', [
                        'user' => $user,
                        'page' => 'annuaire',
                        'status' => $status,
                        'login' => $login,
                        'role' => 'Autre',
                        'directory' => 'Autre',
                        ]);
                }

            }
                // FIN du FILTRE par ROLE

                // DEBUT FILTRE par ANNUAIRE NATIONAL

            if(isset($_GET['page'])){     


                if($_GET['page'] == 'Comite-crise' ){
                    
                  
                
                    $user = $this->getUser();
                    // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                    $em = $this->getDoctrine()->getManager();
    
                    $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '1']);
                    
                    return $this->render('directories/listUser.html.twig', [
                        'user' => $user,
                        'page' => 'annuaire',
                        'status' => $status,
                        'login' => $login,
                        'role' => 'ComiteCrise',
                        'directory' => 'ComiteCrise',

                        ]);
        
                    }elseif($_GET['page'] == 'Comite-elargi' ){
                    
                        $user = $this->getUser();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                        $em = $this->getDoctrine()->getManager();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                    
                    $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '2']);
        
                    return $this->render('directories/listUser.html.twig', [
                        'user' => $user,
                        'page' => 'annuaire',
                        'status' => $status,
                        'login' => $login,
                        'role' => 'ComiteElargi',
                        'directory' => 'ComiteElargi',
                        ]);
        
                    }elseif($_GET['page'] == 'Responsable-site' ){
                        
                        $user = $this->getUser();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                        $em = $this->getDoctrine()->getManager();
    
                        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '3']);
        
                        return $this->render('directories/listUser.html.twig', [
                            'user' => $user,
                            'page' => 'annuaire',
                            'status' => $status,
                            'login' => $login,
                            'role' => 'ResponsableSite',
                            'directory' => 'ResponsableSite',
                            ]);

                    }elseif($_GET['page'] == 'Numeros-importants' ){
                        
                        $user = $this->getUser();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                        $em = $this->getDoctrine()->getManager();
    
                        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '4']);
        
                        return $this->render('directories/listUser.html.twig', [
                            'user' => $user,
                            'page' => 'annuaire',
                            'status' => $status,
                            'login' => $login,
                            'role' => 'NumerosImportants',
                            'directory' => 'NumerosImportants',
                            ]);

                    }elseif($_GET['page'] == 'Non-renseigne' ){
                        
                        $user = $this->getUser();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                        $em = $this->getDoctrine()->getManager();
    
                        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '5']);
        
                        return $this->render('directories/listUser.html.twig', [
                            'user' => $user,
                            'page' => 'annuaire',
                            'status' => $status,
                            'login' => $login,
                            'role' => 'NonRenseigne',
                            'directory' => 'NonRenseigne',
                            ]);
                    }
        }

                         return $this->render('directories/listUser.html.twig', [
                            'user' => $user,
                            'status' => $status,
                            'login' => $login,
                            'page' => 'annuaire',
                            'role' => 'accueil',
                            'directory' => 'accueil'
                        ]);
    

    // FIN DU FILTRE par ANNUAIRE NATIONAL

    // DEBUT FILTRE par ANNUAIRE ORGANISME
        if(isset($_GET['page'])){     

            if($_GET['page'] == 'Comite-crise-organisme' && $status === 'RPCA'){
            
          

                $user = $this->getUser();
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $em = $this->getDoctrine()->getManager();

                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '1']);

                
            
            return $this->render('directories/listUser.html.twig', [
                'user' => $user,
                'page' => 'annuaire',
                'status' => $status,
                'login' => $login,
                'role' => 'ComiteCriseorganisme',
                'directory' => 'ComiteCriseorganisme',

                ]);

            }elseif($_GET['page'] == 'Comite-elargi-organisme' && $status === 'RPCA' ){
            
                $login = $this->getUser();
                dd($login);
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $em = $this->getDoctrine()->getManager();
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $organization = $login->getOrganization()->getId();
                
                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['organization' => $organization], ['directory' => '2']);
                
            return $this->render('directories/listUser.html.twig', [
                'user' => $user,
                'page' => 'annuaire',
                'status' => $status,
                'login' => $login,
                'role' => 'ComiteElargiorganisme',
                'directory' => 'ComiteElargiorganisme',
                ]);

            }elseif($_GET['page'] == 'Responsable-site-Organisme' && $status === 'RPCA' ){
                
                $user = $this->getUser();
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $em = $this->getDoctrine()->getManager();

                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '3']);

                return $this->render('directories/listUser.html.twig', [
                    'user' => $user,
                    'page' => 'annuaire',
                    'status' => $status,
                    'login' => $login,
                    'role' => 'ResponsableSite',
                    'directory' => 'ResponsableSite',
                    ]);

            }elseif($_GET['page'] == 'Numeros-importants-Organisme' && $status === 'RPCA' ){
                
                $user = $this->getUser();
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $em = $this->getDoctrine()->getManager();

                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '4']);

                return $this->render('directories/listUser.html.twig', [
                    'user' => $user,
                    'page' => 'annuaire',
                    'status' => $status,
                    'login' => $login,
                    'role' => 'NumerosImportants',
                    'directory' => 'NumerosImportants',
                    ]);

            }elseif($_GET['page'] == 'Non-renseigne-Organisme' && $status === 'RPCA'){
                
                $user = $this->getUser();
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $em = $this->getDoctrine()->getManager();

                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '5']);

                return $this->render('directories/listUser.html.twig', [
                    'user' => $user,
                    'page' => 'annuaire',
                    'status' => $status,
                    'login' => $login,
                    'role' => 'NonRenseigne',
                    'directory' => 'NonRenseigne',
                    ]);
            }
}

                 return $this->render('directories/listUser.html.twig', [
                    'user' => $user,
                    'page' => 'annuaire',
                    'role' => 'accueil',
                    'directory' => 'accueil'
                ]);
}
    // FIN DU FILTRE par ANNUAIRE ORGANISME

    // DETAILS UTILISATEURS DANS L'ANNUAIRE

    public function DetailsUser(Request $request): Response
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $user= $em->getRepository(User::class)->findOneBy(['id'=> $_GET['user']]);
        
        return $this->render('directories/detailsUser.html.twig', [
            'user' => $user,
        ]);

    }



    // AJOUTS UTILISATEURS DANS L'ANNUAIRE

    public function AddUser(): Response
    {

        $errors = [];

        if(!empty($_POST)){

            $safe= array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();

            $role = $em ->getRepository(Role::Class);
            $grade = $em ->getRepository(Grade::Class);
            $directory = $em ->getRepository(Directory::Class);
            $organization = $em ->getRepository(Organization::Class);
    
            // Je recupere mes données de role et de grade
            $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);

            $safe['directory'] = $directory->FindOneBy(['id' => $safe['directory']]);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            // La variable em c'est EntityManager, c'est la connexion a la base de donnée


            if(!v::length(2, 50)->validate($safe['firstname'])){
                $errors[]= 'Votre prénom doit contenir entre 2 et 50 caracteres';
            }

            if(!v::length(2, 50)->validate($safe['lastname'])){
                $errors[]= 'Votre nom doit contenir entre 2 et 50 caracteres';
            }

            if(!v::length(5, 100)->validate($safe['profession'])){
                $errors[]= 'Votre fonction doit contenir entre 5 et 100 caracteres';
            }

            if(!v::length(10)->validate($safe['mobilenumber'])){
                $errors[]= 'Votre numéro de téléphone portable doit contenir exactement 10 chiffres';
            }

            if(!v::length(10)->validate($safe['phonenumber'])){
                $errors[]= 'Votre numéro de téléphone fixe doit contenir exactement 10 chiffres';
            }

            if(!v::length(5, 50)->validate($safe['structure'])){
                $errors[]= 'Votre Bâtiment doit contenir entre 5 et 50 caractères';
            }

            if(!v::length(null, 15)->validate($safe['floor'])){
                $errors[]= 'Votre Etage ne peut pas excéder 15 caracteres';
            }

	        if(!isset($safe['role'])){ // Validation organisation
                    $errors[] = 'Vous devez choisir votre rôle au sein de MemoPCA.';
            }

	        if(!isset($safe['directory'])){ // Validation organisation
                    $errors[] = 'Vous devez choisir l\'annuaire auquel vous appartenez au sein de MemoPCA.';
            }

	        if(!isset($safe['grade'])){ // Validation organisation
                    $errors[] = 'Vous devez choisir si vous êtes "Titulaire" ou "Suppléant".';
            }

	        if(!isset($safe['organization'])){ // Validation organisation
                    $errors[] = 'Vous devez choisir votre organisme.';
            }

            // Verification Image
            if(isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE){

                if($_FILES['photo']['error'] != UPLOAD_ERR_OK){
                    $errors[] = 'Une erreur est survenue lors du transfert de l\'image'; 
                }
                else {

                    $maxSize = 3 * 1000 * 1000; 

                    if($_FILES['photo']['size'] > $maxSize){
                        $errors[] = 'L\'image est trop volumineuse, maximum 3Mo';
                    }
                    else {

                        $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png'];
                        if(!in_array($_FILES['photo']['type'], $allowMimesTypes)){
                            $errors[] = 'Le type de fichier est invalide';
                        }
                    }
                }
                
            } // endif isset($_FILES['profilPicture']) && !empty($_FILES['profilPicture']) && $_FILES['profilPicture']['error'] != UPLOAD_ERR_NO_FILE

            if(count($errors) === 0){

            // dd($_FILES);
            
            $user = $this->getUser();

            // La variable em c'est EntityManager, c'est la connexion a la base de donnée
            $em = $this->getDoctrine()->getManager();
            
            $role = $em ->getRepository(Role::Class);
            $grade = $em ->getRepository(Grade::Class);
            $directory = $em ->getRepository(Directory::Class);
            $organization = $em ->getRepository(Organization::Class);
    
            // Je recupere mes données de role et de grade
            $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);
            $safe['grade'] = $grade->FindOneBy(['id' => $safe['grade']]);
            $safe['directory'] = $directory->FindOneBy(['id' => $safe['directory']]);
            $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            
            
            // je selectionne la table de la base de données dans laquelle je veux travailler
            $user = new User;
           
            
            // dd($user);
            
            $user->setFirstname($safe['firstname']);
            $user->setLastname($safe['lastname']);
            $user->setProfession($safe['profession']);
            $user->setMobilenumber($safe['mobilenumber']);
            $user->setPhonenumber($safe['phonenumber']);
            $user->setStructure($safe['structure']);
            $user->setFloor($safe['floor']);
            $user->setRole($safe['role']);
            $user->setGrade($safe['grade']);
            $user->setDirectory($safe['directory']);
            $user->setOrganization($safe['organization']);
            
            // PHOTO
            if($_FILES['photo']['error'] === UPLOAD_ERR_OK){
                $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                $publicOutput = 'asset/uploads/pictures/'; // Chemin à partir de public
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
                $user->setPhoto($publicOutput.$filename);
            }


            // Je prepare la sauvegarde en base de donnée
            $em->persist($user);

            // L'équivalent du execute()
            $em->flush();

            //  On crée le message Flash
            $this->addFlash('success',  'Le contact a bien été ajouté dans l\'annuaire.');

            return $this ->redirectToRoute('list_directory_controller', ['page' => 'Accueil'] );

        }else{
            
            $this->addFlash('danger',  implode('<br>', $errors));
            }
        }

        return $this->render('directories/AddUser.html.twig', [
            ]);
    }

    // MODIFICATION UTILISATEURS DANS L'ANNUAIRE

    public function UpdateUser(Request $request): Response
    {
        $errors = [];
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::Class)->FindOneBy(['id' => $_GET['user']]);

        if(!empty($_POST)){

        $safe= array_map('trim', array_map('strip_tags', $_POST));

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::Class)->FindOneBy(['id' => $_GET['user']]);

        $role = $em ->getRepository(Role::Class);
        $grade = $em ->getRepository(Grade::Class);
        $directory = $em ->getRepository(Directory::Class);
        $organization = $em ->getRepository(Organization::Class);

        // Je recupere mes données de role et de grade
        $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);

        $safe['directory'] = $directory->FindOneBy(['id' => $safe['directory']]);
        $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
        // La variable em c'est EntityManager, c'est la connexion a la base de donnée


        if(!v::length(2, 50)->validate($safe['firstname'])){
            $errors[]= 'Votre prénom doit contenir entre 2 et 50 caracteres';
        }

        if(!v::length(2, 50)->validate($safe['lastname'])){
            $errors[]= 'Votre nom doit contenir entre 2 et 50 caracteres';
        }

        if(!v::length(5, 100)->validate($safe['profession'])){
            $errors[]= 'Votre fonction doit contenir entre 5 et 100 caracteres';
        }

        if(!v::length(10)->validate($safe['mobilenumber'])){
            $errors[]= 'Votre numéro de téléphone portable doit contenir exactement 10 chiffres';
        }

        if(!v::length(10)->validate($safe['phonenumber'])){
            $errors[]= 'Votre numéro de téléphone fixe doit contenir exactement 10 chiffres';
        }

        if(!v::length(5, 50)->validate($safe['structure'])){
            $errors[]= 'Votre Bâtiment doit contenir entre 5 et 50 caractères';
        }

        if(!v::length(null, 15)->validate($safe['floor'])){
            $errors[]= 'Votre Etage ne peut pas excéder 15 caracteres';
        }

        if(!isset($safe['role'])){ // Validation organisation
                $errors[] = 'Vous devez choisir votre rôle au sein de MemoPCA.';
        }

        if(!isset($safe['directory'])){ // Validation organisation
                $errors[] = 'Vous devez choisir l\'annuaire auquel vous appartenez au sein de MemoPCA.';
        }

        if(!isset($safe['grade'])){ // Validation organisation
                $errors[] = 'Vous devez choisir si vous êtes "Titulaire" ou "Suppléant".';
        }

        if(!isset($safe['organization'])){ // Validation organisation
                $errors[] = 'Vous devez choisir votre organisme.';
        }

        // Verification Image
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE){

            if($_FILES['photo']['error'] != UPLOAD_ERR_OK){
                $errors[] = 'Une erreur est survenue lors du transfert de l\'image'; 
            }
            else {

                $maxSize = 3 * 1000 * 1000; 

                if($_FILES['photo']['size'] > $maxSize){
                    $errors[] = 'L\'image est trop volumineuse, maximum 3Mo';
                }
                else {

                    $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png'];
                    if(!in_array($_FILES['photo']['type'], $allowMimesTypes)){
                        $errors[] = 'Le type de fichier est invalide';
                    }
                }
            }
            
        } // endif isset($_FILES['profilPicture']) && !empty($_FILES['profilPicture']) && $_FILES['profilPicture']['error'] != UPLOAD_ERR_NO_FILE

        if(count($errors) === 0){

        
        
        $safe= array_map('trim', array_map('strip_tags', $_POST));

        $role = $em ->getRepository(Role::Class);
        $grade = $em ->getRepository(Grade::Class);
        $directory = $em ->getRepository(Directory::Class);
        $organization = $em ->getRepository(Organization::Class);

        $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);
        $safe['grade'] = $grade->FindOneBy(['id' => $safe['grade']]);
        $safe['directory'] = $directory->FindOneBy(['id' => $safe['directory']]);
        $safe['organization'] = $organization->FindOneBy(['id' => $safe['organization']]);
            
        $user->setFirstname($safe['firstname']);
        $user->setLastname($safe['lastname']);
        $user->setProfession($safe['profession']);
        $user->setMobilenumber($safe['mobilenumber']);
        $user->setPhonenumber($safe['phonenumber']);
        $user->setStructure($safe['structure']);
        $user->setFloor($safe['floor']);
        $user->setRole($safe['role']);
        $user->setGrade($safe['grade']);
        $user->setDirectory($safe['directory']);
        $user->setOrganization($safe['organization']);
        
        // PHOTO

        if($_FILES['photo']['error'] === UPLOAD_ERR_OK){
            $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
            $publicOutput = 'asset/uploads/pictures/'; // Chemin à partir de public
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
            $user->setPhoto($publicOutput.$filename);
        }

        

        if($request->isMethod('POST'))

        
        $em = $this->getDoctrine()->getManager();

        // Je prepare la sauvegarde en base de donnée
        $em->persist($user);
        
        // L'équivalent du execute()
        $em->flush();

        // On crée le message flash
        $this->addFlash('success',  'Le contact a bien été modifié.');
        
        return $this->render('directories/detailsUser.html.twig', [
            'user' => $user,
            ]);

        }else{

            $this->addFlash('danger',  implode('<br>', $errors));
        }
 
    }
    return $this->render('directories/updateUser.html.twig', [
        'user' => $user,
        ]);

}



    // SUPPRESSION UTILISATEURS DANS L'ANNUAIRE

    public function DeleteUser(): Response
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository(User::class)->findOneBy(['id'=> $_GET['user']]);

        $em->remove($user);

        $em->flush();

        $this->addFlash('success',  'Le contact a bien été supprimé.');

        return $this->redirectToRoute('list_directory_controller', ['page' => 'Accueil'] );
    }

    // public function JsonDirectory(): Response 
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $user1 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '1']);

    //     $data1 = array();
    //     foreach ($user1 as $key => $user){
    //         $data1[$key]['id'] = "id".$user->getId();
    //         $data1[$key]['fonction'] = $user->getProfession();
    //         $data1[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data1[$key]['portable'] = $user->getMobilenumber();
    //         $data1[$key]['fixe'] = $user-> getPhonenumber();
    //         $data1[$key]['etage'] = $user->getFloor();
    //         $data1[$key]['photo'] = $user-> getPhoto();
    //         $data1[$key]['type'] = $user->getRole()->getName();
    //         $data1[$key]['niveau'] = $user->getGrade()->getName();
    //         $data1[$key]['batiment'] = $user-> getStructure();
    //     }        
        
    //     $response1 = new JsonResponse();
        
    //     $response1->headers->set('Content-Type', 'application/json');
    //     $response1->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response1->setData($data1);

    //     $user2 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '2']);

    //     $data2 = array();
        
    //     foreach ($user2 as $key => $user){
    //         $data2[$key]['id'] = "id".$user->getId();
    //         $data2[$key]['fonction'] = $user->getProfession();
    //         $data2[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data2[$key]['portable'] = $user->getMobilenumber();
    //         $data2[$key]['fixe'] = $user-> getPhonenumber();
    //         $data2[$key]['etage'] = $user->getFloor();
    //         $data2[$key]['photo'] = $user-> getPhoto();
    //         $data2[$key]['type'] = $user->getRole()->getName();
    //         $data2[$key]['niveau'] = $user->getGrade()->getName();
    //         $data2[$key]['batiment'] = $user-> getStructure();
    //     }

    //     $response2 = new JsonResponse();
        
    //     $response2->headers->set('Content-Type', 'application/json');
    //     $response2->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response2->setData($data2);
        
    //     $user3 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '3']);

    //     $data3 = array();
        
    //     foreach ($user3 as $key => $user){
    //         $data3[$key]['id'] = "id".$user->getId();
    //         $data3[$key]['fonction'] = $user->getProfession();
    //         $data3[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data3[$key]['portable'] = $user->getMobilenumber();
    //         $data3[$key]['fixe'] = $user-> getPhonenumber();
    //         $data3[$key]['etage'] = $user->getFloor();
    //         $data3[$key]['photo'] = $user-> getPhoto();
    //         $data3[$key]['type'] = $user->getRole()->getName();
    //         $data3[$key]['niveau'] = $user->getGrade()->getName();
    //         $data3[$key]['batiment'] = $user-> getStructure();
    //     }

    //     $response3 = new JsonResponse();
        
    //     $response3->headers->set('Content-Type', 'application/json');
    //     $response3->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response3->setData($data3);

    //     $user4 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '4']);

    //     $data4 = array();
        
    //     foreach ($user4 as $key => $user){
    //         $data4[$key]['id'] = "id".$user->getId();
    //         $data4[$key]['fonction'] = $user->getProfession();
    //         $data4[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data4[$key]['portable'] = $user->getMobilenumber();
    //         $data4[$key]['fixe'] = $user-> getPhonenumber();
    //         $data4[$key]['etage'] = $user->getFloor();
    //         $data4[$key]['photo'] = $user-> getPhoto();
    //         $data4[$key]['type'] = $user->getRole()->getName();
    //         $data4[$key]['niveau'] = $user->getGrade()->getName();
    //         $data4[$key]['batiment'] = $user-> getStructure();
    //     }

    //     $response4 = new JsonResponse();
        
    //     $response4->headers->set('Content-Type', 'application/json');
    //     $response4->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response4->setData($data4);

    //     $user5 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '5']);

    //     $data5 = array();
        
    //     foreach ($user5 as $key => $user){
    //         $data5[$key]['id'] = "id".$user->getId();
    //         $data5[$key]['fonction'] = $user->getProfession();
    //         $data5[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data5[$key]['portable'] = $user->getMobilenumber();
    //         $data5[$key]['fixe'] = $user-> getPhonenumber();
    //         $data5[$key]['etage'] = $user->getFloor();
    //         $data5[$key]['photo'] = $user-> getPhoto();
    //         $data5[$key]['type'] = $user->getRole()->getName();
    //         $data5[$key]['niveau'] = $user->getGrade()->getName();
    //         $data5[$key]['batiment'] = $user-> getStructure();
    //     }

    //     $response5 = new JsonResponse();
        
    //     $response5->headers->set('Content-Type', 'application/json');
    //     $response5->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response5->setData($data5);


    //     $files = array($response1, $response2, $response3, $response4, $response5);
    //     $zipname = 'Annuaires.zip';
    //     $zip = new ZipArchive;
        
    //     if($zip->open($zipname, ZipArchive::CREATE) == TRUE ){
    //     foreach ($files as $file) {
    //     // download file

        
    //     $numFiles = $zip->numFiles;
        
    //     dd($numFiles);
        
    //     }

    //     $zip->close();
    // }
    //     header('Content-Type: application/zip');
    //     header('Content-disposition: attachment; filename='.$zipname);
    //     header('Content-Length: ' . filesize($zipname));
    //     readfile($zipname);

    //     unlink($zipname);
        
        
    //     return $response;
    // }

    public function JsonDirectory1(): Response
    {
        
        // $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user1 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '1']);

        $data1 = array();
        foreach ($user1 as $key => $user){
            $data1[$key]['id'] = "id".$user->getId();
            $data1[$key]['fonction'] = $user->getProfession();
            $data1[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
            $data1[$key]['portable'] = $user->getMobilenumber();
            $data1[$key]['fixe'] = $user-> getPhonenumber();
            $data1[$key]['etage'] = $user->getFloor();
            $data1[$key]['photo'] = $user-> getPhoto();
            $data1[$key]['type'] = $user->getRole()->getName();
            $data1[$key]['niveau'] = $user->getGrade()->getName();
            $data1[$key]['batiment'] = $user-> getStructure();
        }        
        
        $response = new JsonResponse();
        
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $response->setData($data1);
        return $response;

    }

    public function JsonDirectory2(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user2 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '2']);


        $data2 = array();
        
        foreach ($user2 as $key => $user){
            $data2[$key]['id'] = "id".$user->getId();
            $data2[$key]['fonction'] = $user->getProfession();
            $data2[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
            $data2[$key]['portable'] = $user->getMobilenumber();
            $data2[$key]['fixe'] = $user-> getPhonenumber();
            $data2[$key]['etage'] = $user->getFloor();
            $data2[$key]['photo'] = $user-> getPhoto();
            $data2[$key]['type'] = $user->getRole()->getName();
            $data2[$key]['niveau'] = $user->getGrade()->getName();
            $data2[$key]['batiment'] = $user-> getStructure();
        }

        $response = new JsonResponse();
        
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $response->setData($data2);
        return $response;
    }

    public function JsonDirectory3(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user3 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '3']);


        $data3 = array();
        foreach ($user3 as $key => $user){
            $data3[$key]['id'] = "id".$user->getId();
            $data3[$key]['fonction'] = $user->getProfession();
            $data3[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
            $data3[$key]['portable'] = $user->getMobilenumber();
            $data3[$key]['fixe'] = $user-> getPhonenumber();
            $data3[$key]['etage'] = $user->getFloor();
            $data3[$key]['photo'] = $user-> getPhoto();
            $data3[$key]['type'] = $user->getRole()->getName();
            $data3[$key]['niveau'] = $user->getGrade()->getName();
            $data3[$key]['batiment'] = $user-> getStructure();
        }

        $response = new JsonResponse();
        
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $response->setData($data3);
        return $response;

        }

    public function JsonDirectory4(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user4 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '4']);
    
        $data4 = array();
        foreach ($user4 as $key => $user){
            $data4[$key]['id'] = "id".$user->getId();
            $data4[$key]['fonction'] = $user->getProfession();
            $data4[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
            $data4[$key]['portable'] = $user->getMobilenumber();
            $data4[$key]['fixe'] = $user-> getPhonenumber();
            $data4[$key]['etage'] = $user->getFloor();
            $data4[$key]['photo'] = $user-> getPhoto();
            $data4[$key]['type'] = $user->getRole()->getName();
            $data4[$key]['niveau'] = $user->getGrade()->getName();
            $data4[$key]['batiment'] = $user-> getStructure();
        }

        $response = new JsonResponse();
        
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $response->setData($data4);
        return $response;
    }

    public function JsonDirectory5(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user5 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '5']);
        
        $data5 = array();
        foreach ($user5 as $key => $user){
            $data5[$key]['id'] = "id".$user->getId();
            $data5[$key]['fonction'] = $user->getProfession();
            $data5[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
            $data5[$key]['portable'] = $user->getMobilenumber();
            $data5[$key]['fixe'] = $user-> getPhonenumber();
            $data5[$key]['etage'] = $user->getFloor();
            $data5[$key]['photo'] = $user-> getPhoto();
            $data5[$key]['type'] = $user->getRole()->getName();
            $data5[$key]['niveau'] = $user->getGrade()->getName();
            $data5[$key]['batiment'] = $user-> getStructure();
        }

        $response = new JsonResponse();
        
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $response->setData($data5);
        return $response;
    }

}

    
