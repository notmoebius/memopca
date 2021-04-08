<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Respect\Validation\Validator as v;
use Symfony\Component\Validator\Validator\ValidatorInterface;



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

    public function detailsUser(Request $request): Response
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $details= $em->getRepository(User::class)->findOneBy(['id'=> $_GET['id']]);

        return $this->render('directories/detailsUser.html.twig', [
            'user' => $user,
        ]);

    }

    public function addUser(): Response
    {
                $safe= array_map('trim', array_map('strip_tags', $_POST));

                $user = new User;
                $em = $this->getDoctrine()->getManager();
                
                // dd($user);
 
                $user->setFirstname($safe['firstname']);
                $user->setLastname($safe['lastname']);
                $user->setStatus($safe['status']);
                $user->setMobilenumber($safe['mobilenumber']);
                $user->setPhonenumber($safe['phonenumber']);
                $user->setStructure($safe['structure']);
                $user->setFloor($safe['floor']);
                
                $user->setGrade(boolval(1));


                $em->persist($user);
                $em->flush();

                return $this->redirectTORoute('list_directory_controller');
            }

    
}
