<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Grade;
use Cnam\ValidatorBundle\Constraints\Diademe\Diademe;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;



class DirectoriesController extends AbstractController
{

    // LISTING UTILISATEURS DANS L'ANNUAIRE

    public function listUser(Request $request): Response
    {
        $user = $this->getUser();
       
        if(isset($_GET['page'])){     

            if($_GET['page'] == 'accueil'){

            $user = $this->getUser();
            // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getDoctrine()->getRepository(User::class)->findAll();

            return $this->render('directories/listUser.html.twig', [
                'user' => $user,
                'page' => 'annuaire',
                'role' => 'accueil',
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
                        ]);
                }
            }
            return $this->render('directories/listUser.html.twig', [
                'user' => $user,
                'page' => 'annuaire',
                'role' => 'accueil',
            ]);
    }

    // DETAILS UTILISATEURS DANS L'ANNUAIRE

    public function detailsUser(Request $request): Response
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $user= $em->getRepository(User::class)->findOneBy(['id'=> $_GET['user']]);
        
        return $this->render('directories/detailsUser.html.twig', [
            'user' => $user,
            'role' => 'accueil'
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

            // Je recupere mes données de role et de grade
            $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);
            $safe['grade'] = $grade->FindOneBy(['id' => $safe['grade']]);
            
            
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

            return $this ->redirectToRoute('list_directory_controller');

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


    if(!empty($_POST)){

        
        
        $safe= array_map('trim', array_map('strip_tags', $_POST));
        $role = $em ->getRepository(Role::Class);
        $grade = $em ->getRepository(Grade::Class);

        $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);
        $safe['grade'] = $grade->FindOneBy(['id' => $safe['grade']]);
            
        $user->setFirstname($safe['firstname']);
        $user->setLastname($safe['lastname']);
        $user->setStatus($safe['status']);
        $user->setMobilenumber($safe['mobilenumber']);
        $user->setPhonenumber($safe['phonenumber']);
        $user->setStructure($safe['structure']);
        $user->setFloor($safe['floor']);
        $user->setRole($safe['role']);
        $user->setGrade($safe['grade']);
        
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
        // Je prepaer la sauvegarde en base de donnée
        $em->persist($user);

        // L'équivalent du execute()
        $em->flush();

        
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

        return $this->redirectToRoute('list_directory_controller');
    }

}
