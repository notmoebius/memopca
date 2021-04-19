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

        $user = $this->getUser();
               
                $em = $this->getDoctrine()->getManager();

                $user = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('memo/listMemo.html.twig', [
            'list_memo_controller' => 'MemoController',
            'user' => $user,
        ]);
    }

    public function AddMemo(): Response
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository(User::class)->findAll();
        $memo = new Memo();

        $form = $this->createForm(MemoType::class, $memo);
        
        return $this->render('memo/addMemo.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
