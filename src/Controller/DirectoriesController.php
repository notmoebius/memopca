<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Respect\Validation\Validator as v;

class DirectoriesController extends AbstractController
{

    public function listUser(): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $user= $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('directories/listUser.html.twig', [
            'user' => $user,
        ]);

    }

    public function addUser(): Response
    {

        $user = $this->getUser();
        
        $errors = [];

        if(!empty($_POST)){

            $safe=array_map('trim', array_map('strip_tags', $_POST));

            if(!v::length(3, 50)->validate($safe['firstname'])){
                $errors[]= 'Votre prénom doit contenir entre 3 et 50 caracteres';
            }

            if(!v::length(3, 50)->validate($safe['lastname'])){
                $errors[]= 'Votre nom doit contenir entre 3 et 50 caracteres';
            }

            if(!v::length(5, 100)->validate($safe['status'])){
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

            if(!v::length(null, 15)->validate($safe['structure'])){
                $errors[]= 'Votre Etage ne peut pas excéder 15 caracteres';
            }

            // Verification Image
            if(isset($_FILES['photo']) && !empty($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE){

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
            else {
                $errors[] = 'Vous devez sélectionner une image';
            }

            if(count($errors) === 0){
        
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
                }
                
                $user = $this->getUser();
                
                $user->setFirstname($safe['firstname']);
                $user->setLastname($safe['lastname']);
                $user->setStatus($safe['status']);
                $user->setMobilenumber($safe['mobilenumber']);
                $user->setPhonenumber($safe['phonenumber']);
                $user->setStructure($safe['structure']);
                $user->setFloor($safe['floor']);
                $user->setRole($safe['role']);
                $user->setGrade($safe['grade']);
                $user->setPhoto($publicOutput.$filename);

                $em->persist($user);
                $em->flush();

                return $this->redirectTORoute('list_directory_controller');
            }else{
             $this->addFlash('danger', implode('<br>', $errors));
            } // End if count($errors) === 0


        }
        return $this->render('directories/addUser.html.twig', [
            'add_directory_controller' => 'DirectoriesController',
        ]);
    }
}