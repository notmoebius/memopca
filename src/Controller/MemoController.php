<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Grade;
use App\Entity\Directory;
use App\Entity\Memo;
use App\Form\MemoType;

class MemoController extends AbstractController
{
    /**
     * @Route("/memo", name="memo")
     */
    public function ListMemo(): Response
    {

        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        
        return $this->render('memo/listMemo.html.twig', [
            'list_memo_controller' => 'MemoController',
            'user' => $user,
            'login' => $login,
            'status' => $status,
            ]);
    }

    public function AddMemo(): Response
    {

        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository(User::class)->findAll();
        $memo = new Memo();


        
        return $this->render('memo/addMemo.html.twig', [
            'user' => $user,
            'login' => $login,
            'status' => $status,

        ]);
    }
}
