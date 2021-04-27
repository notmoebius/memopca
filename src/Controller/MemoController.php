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
use App\Entity\Memo;
use App\Form\MemoType;
use Doctrine\Common\Collections\ArrayCollection;

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

        $memo= $em->getRepository(Memo::class)->findAll();

        
        return $this->render('memo/listMemo.html.twig', [
            'list_memo_controller' => 'MemoController',
            
            'login' => $login,
            'status' => $status,
            'memo' => $memo,
            ]);
    }

    public function AddMemo(): Response
    {

        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository(User::class)->findAll();
        $memo = $em->getRepository(Memo::class);

        if(!empty($_POST)){

            $safe= array_map('trim', array_map('strip_tags', $_POST));
            $user= $em->getRepository(User::class);

            
            // Je recupere mes données de role et de grade
            $safe['users'] = $user->FindOneBy(['id' => $safe['users']]);
            $safe['informed'] = $user->FindOneBy(['id' => $safe['informed']]);
            $safe['informed2'] = $user->FindOneBy(['id' => $safe['informed2']]);
            $safe['inform'] = $user->FindOneBy(['id' => $safe['inform']]);
            $safe['inform2'] = $user->FindOneBy(['id' => $safe['inform2']]);

            
            $memo = new Memo();
            
            $memo->setUsers($safe['users']);
            $memo->setInformed1($safe['informed']);
            
            $memo->setInformed2($safe['informed2']);
            
            $memo->setInform1($safe['inform']);
            
            $memo->setInform2($safe['inform2']);
            
            $memo->setCreatedAt(new \DateTime('now'));
            
            $em->persist($memo);
            $em->flush();
            $_POST = array();

            return $this->render('memo/detailsMemo.html.twig', [
                'login'=> $login,
                'status' => $status,
                'memo' => $memo,
                ]);
            
        }
        return $this->render('memo/addMemo.html.twig', [
            'user' =>$user,
            'memo' => $memo,
            'login' => $login,
            'status' => $status,

        ]);
    }

    // DETAILS Memo

    public function DetailsMemo(Request $request): Response
    {

        $login = $this->getUser();
        $status = $this->getUser()->getStatus();
        $em = $this->getDoctrine()->getManager();
        
        $memo= $em->getRepository(Memo::class)->findOneBy(['id'=> $_GET['memo']]);
        
        return $this->render('memo/detailsMemo.html.twig', [
            'login'=> $login,
            'status' => $status,
            'memo' => $memo,
        ]);

    }

    public function UpdateMemo(Request $request): Response
    {
        
        $login = $this->getUser();
        $status = $this->getUser()->getStatus();

        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository(User::class)->findAll();
        $memo = $em->getRepository(Memo::class)->findOneBy(['id' => $_GET['memo']]);

        if(!empty($_POST)){

            $safe= array_map('trim', array_map('strip_tags', $_POST));
            $user= $em->getRepository(User::class);

            
            // Je recupere mes données de role et de grade
            $safe['users'] = $user->FindOneBy(['id' => $safe['users']]);
            $safe['informed'] = $user->FindOneBy(['id' => $safe['informed']]);
            $safe['informed2'] = $user->FindOneBy(['id' => $safe['informed2']]);
            $safe['inform'] = $user->FindOneBy(['id' => $safe['inform']]);
            $safe['inform2'] = $user->FindOneBy(['id' => $safe['inform2']]);

            
            
            $memo->setUsers($safe['users']);
            $memo->setInformed1($safe['informed']);
            
            $memo->setInformed2($safe['informed2']);
            
            $memo->setInform1($safe['inform']);
            
            $memo->setInform2($safe['inform2']);
            
            $memo->setUpdatedAt(new \DateTime('now'));

            if($request->isMethod('POST'))

            $login = $this->getUser();
            $status = $this->getUser()->getStatus();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($memo);
            $em->flush();
            $_POST = array();
            
            return $this->render('memo/detailsMemo.html.twig', [
                'login'=> $login,
                'status' => $status,
                'memo' => $memo,
                
                ]);
        }
        return $this->render('memo/updateMemo.html.twig', [
            'user' =>$user,
            'memo' => $memo,
            'login' => $login,
            'status' => $status,
        ]);
    }

        // SUPPRESSION MEMO

        public function DeleteMemo(): Response
        {
    
            $login = $this->getUser();
            $status = $this->getUser()->getStatus();
    
            $em = $this->getDoctrine()->getManager();
            $memo= $em->getRepository(Memo::class)->findOneBy(['id'=> $_GET['memo']]);
    
            $em->remove($memo);
    
            $em->flush();
    
            $this->addFlash('success',  'Le mémo a bien été supprimé.');
    
            return $this->redirectToRoute('list_memo_controller');
        }
}
