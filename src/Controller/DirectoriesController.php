<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Grade;
use App\Entity\Directory;
use Cnam\ValidatorBundle\Constraints\Diademe\Diademe;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;



class DirectoriesController extends AbstractController
{

    // LISTING UTILISATEURS DANS L'ANNUAIRE

    public function listUser(Request $request): Response
    {
        $user = $this->getUser();
       
        // FILTRE PAR ROLE

        if(isset($_GET['page'])){     

            if($_GET['page'] == 'Accueil'){

            $user = $this->getUser();
            // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getDoctrine()->getRepository(User::class)->findAll();

            return $this->render('directories/listUser.html.twig', [
                'user' => $user,
                'page' => 'annuaire',
                'role' => 'all',
                'directory' =>'all'
            ]);
    
            }elseif($_GET['page'] == 'RPCA' ){

                $user = $this->getUser();
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $em = $this->getDoctrine()->getManager();

                $user = $this->getDoctrine()->getRepository(User::class)->findBy(['role' => '1']);
                
                return $this->render('directories/listUser.html.twig', [
                    'user' => $user,
                    'page' => 'annuaire',
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
                        'role' => 'Autre',
                        'directory' => 'Autre',
                        ]);
                }

            }
                // FIN du FILTRE par ROLE

                // DEBUT FILTRE par ANNUAIRE

            if(isset($_GET['page'])){     


                if($_GET['page'] == 'Comité-crise' ){
    
                    $user = $this->getUser();
                    // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                    $em = $this->getDoctrine()->getManager();
    
                    $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '1']);
                    
                    return $this->render('directories/listUser.html.twig', [
                        'user' => $user,
                        'page' => 'annuaire',
                        'role' => 'ComitéCrise',
                        'directory' => 'ComitéCrise',
                        ]);
        
                    }elseif($_GET['page'] == 'Comité-élargi' ){
                    
                        $user = $this->getUser();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                        $em = $this->getDoctrine()->getManager();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                    
                    $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '2']);
        
                    return $this->render('directories/listUser.html.twig', [
                        'user' => $user,
                        'page' => 'annuaire',
                        'role' => 'ComitéElargi',
                        'directory' => 'ComitéElargi',
                        ]);
        
                    }elseif($_GET['page'] == 'Responsable-site' ){
                        
                        $user = $this->getUser();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                        $em = $this->getDoctrine()->getManager();
    
                        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '3']);
        
                        return $this->render('directories/listUser.html.twig', [
                            'user' => $user,
                            'page' => 'annuaire',
                            'role' => 'ResponsableSite',
                            'directory' => 'ResponsableSite',
                            ]);

                    }elseif($_GET['page'] == 'Numéros-importants' ){
                        
                        $user = $this->getUser();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                        $em = $this->getDoctrine()->getManager();
    
                        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '4']);
        
                        return $this->render('directories/listUser.html.twig', [
                            'user' => $user,
                            'page' => 'annuaire',
                            'role' => 'NumérosImportants',
                            'directory' => 'NumérosImportants',
                            ]);

                    }elseif($_GET['page'] == 'Non-renseigné' ){
                        
                        $user = $this->getUser();
                        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                        $em = $this->getDoctrine()->getManager();
    
                        $user = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '5']);
        
                        return $this->render('directories/listUser.html.twig', [
                            'user' => $user,
                            'page' => 'annuaire',
                            'role' => 'NonRenseigné',
                            'directory' => 'NonRenseigné',
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

    // FIN DU FILTRE par ANNUAIRE


    // DETAILS UTILISATEURS DANS L'ANNUAIRE

    public function detailsUser(Request $request): Response
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $user= $em->getRepository(User::class)->findOneBy(['id'=> $_GET['user']]);
        
        return $this->render('directories/detailsUser.html.twig', [
            'user' => $user,
        ]);

    }



    // AJOUTS UTILISATEURS DANS L'ANNUAIRE

    public function addUser(): Response
    {
        if(!empty($_POST)){

            // dd($_FILES);
            
            $user = $this->getUser();
            $safe= array_map('trim', array_map('strip_tags', $_POST));

            // La variable em c'est EntityManager, c'est la connexion a la base de donnée
            $em = $this->getDoctrine()->getManager();
            $role = $em ->getRepository(Role::Class);
            $grade = $em ->getRepository(Grade::Class);
            $directory = $em ->getRepository(Directory::Class);

            // Je recupere mes données de role et de grade
            $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);
            $safe['grade'] = $grade->FindOneBy(['id' => $safe['grade']]);
            $safe['directory'] = $directory->FindOneBy(['id' => $safe['directory']]);
            
            
            // je selectionne la table de la base de données dans laquelle je veux travailler
            $user = new User;
           
            
            // dd($user);
            
            $user->setFirstname($safe['firstname']);
            $user->setLastname($safe['lastname']);
            $user->setStatus($safe['status']);
            $user->setMobilenumber($safe['mobilenumber']);
            $user->setPhonenumber($safe['phonenumber']);
            $user->setStructure($safe['structure']);
            $user->setFloor($safe['floor']);
            $user->setRole($safe['role']);
            $user->setGrade($safe['grade']);
            $user->setDirectory($safe['directory']);
            
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

            return $this->render('directories/AddUser.html.twig', [
            
            ]);
        }
    }


    // MODIFICATION UTILISATEURS DANS L'ANNUAIRE

    public function UpdateUser(Request $request): Response
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
     
        $user= $em->getRepository(User::class)->findOneBy(['id'=> $_GET['user']]);
        $role = $user ->getRole() -> getName();
        $grade = $user ->getGrade() -> getName();
        $directory = $user ->getDirectory() -> getName();


    if(!empty($_POST)){

        
        
        $safe= array_map('trim', array_map('strip_tags', $_POST));
        $role = $em ->getRepository(Role::Class);
        $grade = $em ->getRepository(Grade::Class);
        $directory = $em ->getRepository(Directory::Class);

        $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);
        $safe['grade'] = $grade->FindOneBy(['id' => $safe['grade']]);
        $safe['directory'] = $directory->FindOneBy(['id' => $safe['directory']]);
            
        $user->setFirstname($safe['firstname']);
        $user->setLastname($safe['lastname']);
        $user->setStatus($safe['status']);
        $user->setMobilenumber($safe['mobilenumber']);
        $user->setPhonenumber($safe['phonenumber']);
        $user->setStructure($safe['structure']);
        $user->setFloor($safe['floor']);
        $user->setRole($safe['role']);
        $user->setGrade($safe['grade']);
        $user->setDirectory($safe['directory']);
        
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

}
