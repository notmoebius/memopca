<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Role;
use Cnam\ValidatorBundle\Constraints\Diademe\Diademe;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;



class DirectoriesController extends AbstractController
{

    // LISTING UTILISATEURS DANS L'ANNUAIRE

    public function listUser(): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $user= $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('directories/listUser.html.twig', [
            'user' => $user,
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
        ]);

    }

    // AJOUTS UTILISATEURS DANS L'ANNUAIRE

    public function addUser(): Response
    {
        if(!empty($_POST)){

            $user = $this->getUser();
            $safe= array_map('trim', array_map('strip_tags', $_POST));
            $em = $this->getDoctrine()->getManager();
            $role = $em ->getRepository(Role::Class);
            $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);
            // La variable em c'est EntityManager, c'est la connexion a la base de donnée
            

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
            $user->setGrade(boolval($user));


            // Je prepaer la sauvegarde en base de donnée
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

        if(!empty($_POST)){


        $safe= array_map('trim', array_map('strip_tags', $_POST));
        $role = $em ->getRepository(Role::Class);

        $safe['role'] = $role->FindOneBy(['id' => $safe['role']]);
            
        $user->setFirstname($safe['firstname']);
        $user->setLastname($safe['lastname']);
        $user->setStatus($safe['status']);
        $user->setMobilenumber($safe['mobilenumber']);
        $user->setPhonenumber($safe['phonenumber']);
        $user->setStructure($safe['structure']);
        $user->setFloor($safe['floor']);
        $user->setRole($safe['role']);
        $user->setGrade(boolval($user));

        if($request->isMethod('POST'))

        $em = $this->getDoctrine()->getManager();
        // Je prepaer la sauvegarde en base de donnée
        $em->persist($user);

        // L'équivalent du execute()
        $em->flush();


        }else{


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
