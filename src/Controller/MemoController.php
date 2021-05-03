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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Respect\Validation\Validator as v;

class MemoController extends AbstractController
{
    /**
     * @Route("/memo", name="memo")
     */
    public function ListMemo(): Response
    {

        if(isset($_GET['page'])){

            $login = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(User::class);
            $memo = $this->getDoctrine()->getRepository(User::class);
            $status = $this->getUser()->getStatus();

        if($_GET['page'] == 'Memo-Utilisateur' && $status === 'Utilisateur' ){
                
            $login = $this->getUser();
            // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            $em = $this->getDoctrine()->getManager();

            $login = $login->getId();
            
            $memo = $this->getDoctrine()->getRepository(Memo::class)->findBy(array('login' => $login));

            return $this->render('memo/listMemo.html.twig', [
                'user' => $user,
                'memo' => $memo,
                'page' => 'Memo-Utilisateur',
                'status' => $status,
                'login' => $login,
                ]);

            }elseif($_GET['page'] == 'Accueil' && $status === 'SNPCA' || $status === 'RPCA' ){

                $login = $this->getUser();
                $status = $this->getUser()->getStatus();
        
                $em = $this->getDoctrine()->getManager();
        
                $memo= $em->getRepository(Memo::class)->findAll();
        
                
                return $this->render('memo/listMemo.html.twig', [
                    'list_memo_controller' => 'MemoController',
                    'page' => 'Accueil',
                    'login' => $login,
                    'status' => $status,
                    'memo' => $memo,
                    ]);
        }

    }
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
        
        $errors = [];

        if(!empty($_POST)){

            $safe= array_map('trim', array_map('strip_tags', $_POST));
            $user= $em->getRepository(User::class)->findAll();

            $users = $em->getRepository(User::class)->findby(['id' => $safe['users']]);
            $memo_user = $em->getRepository(Memo::class)->findby(['users' => $safe['users']]);
        
            
            
            if(null == $safe['users'] ){ // Validation organisation
                $errors[] = 'Vous devez choisir un utilisateur au sein de l\'annuaire MemoPCA.';
            }

           if($memo_user){ // Validation users
                  $errors[] = 'Le mémo de cet utilisateur a déjà été crée.';
             }

            if( ($safe['users'] !== 0 && v::equals($safe['users'])->validate($safe['informed'])) || ($safe['users'] !== 0 && v::equals($safe['users'])->validate($safe['informed2'])) || ($safe['users'] !== 0 && v::equals($safe['users'])->validate($safe['inform'])) || ($safe['users'] !== 0 && v::equals($safe['users'])->validate($safe['inform2'])) ){
                $errors[] = 'Il n\'est pas possible de figurer parmis les contacts de son propre mémo.';
            }

            if( (($safe['informed'] > 0) && v::equals($safe['informed'])->validate($safe['informed2'])) || (($safe['informed'] > 0) && v::equals($safe['informed'])->validate($safe['inform'])) || (($safe['informed'] > 0) && v::equals($safe['informed'])->validate($safe['inform2'])) || (($safe['informed2'] > 0) && v::equals($safe['informed2'])->validate($safe['informed'])) || (($safe['informed2'] > 0) && v::equals($safe['informed2'])->validate($safe['inform'])) || (($safe['informed2'] > 0) && v::equals($safe['informed2'])->validate($safe['inform2'])) || (($safe['inform'] > 0) && v::equals($safe['inform'])->validate($safe['informed'])) ||  (($safe['inform'] > 0) && v::equals($safe['inform'])->validate($safe['informed2'])) || (($safe['inform'] > 0) && v::equals($safe['inform'])->validate($safe['inform2'])) || (($safe['inform2'] > 0) && v::equals($safe['inform2'])->validate($safe['informed'])) || (($safe['inform2'] > 0) && v::equals($safe['inform2'])->validate($safe['informed2'])) || (($safe['inform2'] > 0) && v::equals($safe['inform2'])->validate($safe['inform'])) ){
                $errors[] = 'Un contact ne peut pas apparaître plusieurs fois sur le même mémo.';
            }

            if(count($errors) === 0){

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
            
        }else{
            $this->addFlash('danger',  implode('<br>', $errors));

            return $this->redirectToroute('add_memo_controller');
        }
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
        $user= $em->getRepository(User::class)->findAll();

        $memo= $em->getRepository(Memo::class)->findOneBy(['id'=> $_GET['memo']]);
        
        return $this->render('memo/detailsMemo.html.twig', [
            'user' =>$user,
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

        $errors= [];

        if(!empty($_POST)){

            $safe= array_map('trim', array_map('strip_tags', $_POST));
            $user= $em->getRepository(User::class);
            $safe= array_map('trim', array_map('strip_tags', $_POST));
            $user= $em->getRepository(User::class)->findAll();

    

          if( ($safe['users'] !== 0 && v::equals($safe['users'])->validate($safe['informed'])) || ($safe['users'] !== 0 && v::equals($safe['users'])->validate($safe['informed2'])) || ($safe['users'] !== 0 && v::equals($safe['users'])->validate($safe['inform'])) || ($safe['users'] !== 0 && v::equals($safe['users'])->validate($safe['inform2'])) ){
              $errors[] = 'Il n\'est pas possible de figurer parmis les contacts de son propre mémo.';
        }

          if( (($safe['informed'] > 0) && v::equals($safe['informed'])->validate($safe['informed2'])) || (($safe['informed'] > 0) && v::equals($safe['informed'])->validate($safe['inform'])) || (($safe['informed'] > 0) && v::equals($safe['informed'])->validate($safe['inform2'])) || (($safe['informed2'] > 0) && v::equals($safe['informed2'])->validate($safe['informed'])) || (($safe['informed2'] > 0) && v::equals($safe['informed2'])->validate($safe['inform'])) || (($safe['informed2'] > 0) && v::equals($safe['informed2'])->validate($safe['inform2'])) || (($safe['inform'] > 0) && v::equals($safe['inform'])->validate($safe['informed'])) ||  (($safe['inform'] > 0) && v::equals($safe['inform'])->validate($safe['informed2'])) || (($safe['inform'] > 0) && v::equals($safe['inform'])->validate($safe['inform2'])) || (($safe['inform2'] > 0) && v::equals($safe['inform2'])->validate($safe['informed'])) || (($safe['inform2'] > 0) && v::equals($safe['inform2'])->validate($safe['informed2'])) || (($safe['inform2'] > 0) && v::equals($safe['inform2'])->validate($safe['inform'])) ){
            $errors[] = 'Un contact ne peut pas apparaître plusieurs fois sur le même mémo.';
        }

            if(count($errors) === 0){

            $user= $em->getRepository(User::class);
            // Je recupere mes données de role et de grade
            $safe['users'] = $user->FindOneBy(['id' => $safe['users']]);
            $safe['informed'] = $user->FindOneBy(['id' => $safe['informed']]);
            $safe['informed2'] = $user->FindOneBy(['id' => $safe['informed2']]);
            $safe['inform'] = $user->FindOneBy(['id' => $safe['inform']]);
            $safe['inform2'] = $user->FindOneBy(['id' => $safe['inform2']]);

            
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
                'user' => $user,
                'login'=> $login,
                'status' => $status,
                'memo' => $memo,
                
                ]);
        }else{
            $this->addFlash('danger',  implode('<br>', $errors));

            return $this->render('memo/updateMemo.html.twig', [
                'user' =>$user,
                'memo' => $memo,
                'login' => $login,
                'status' => $status,
            ]);
        }
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

            // EXPORT EN JSON
        public function JsonMemo(): Response
        {
            
            
            // $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $memo = $this->getDoctrine()->getRepository(Memo::class)->findAll();

            $data = array();
            foreach ($memo as $memo){

                $serializer = new Serializer();
                $data['qui'] = "id".$memo->getUsers()->getId();

                $data['prevenu'] = ["id".$memo->getInformed1()->getId().", "."id".$memo->getInformed2()->getId()];
                $data['previent'] = ["id".$memo->getInform1()->getId().", "."id".$memo->getInform2()->getId().""];
                $response = new JsonResponse();
            
                $response->headers->set('Content-Type', 'application/json');
                $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
                $response->setData($data);
                return $response;
            
            }  
// dd($data);


        }


    }

