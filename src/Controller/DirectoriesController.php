<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DirectoriesController extends AbstractController
{

    public function list(): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $user= $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('directories/list.html.twig', [
            'user' => $user,
        ]);
    }

    public function add(): Response
    {
        return $this->render('directories/add.html.twig', [
            'add_directory_controller' => 'DirectoriesController',
        ]);
    }
}
