<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DirectoriesController extends AbstractController
{

    public function list(): Response
    {
        return $this->render('directories/list.html.twig', [
            'list_directory_controller' => 'DirectoriesController',
        ]);
    }

    public function add(): Response
    {
        return $this->render('directories/add.html.twig', [
            'add_directory_controller' => 'DirectoriesController',
        ]);
    }
}
