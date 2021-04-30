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
// public function AddmemoJson(): Response 
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $user1 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '1']);

    //     $data1 = array();
    //     foreach ($user1 as $key => $user){
    //         $data1[$key]['id'] = "id".$user->getId();
    //         $data1[$key]['fonction'] = $user->getProfession();
    //         $data1[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data1[$key]['portable'] = $user->getMobilenumber();
    //         $data1[$key]['fixe'] = $user-> getPhonenumber();
    //         $data1[$key]['etage'] = $user->getFloor();
    //         $data1[$key]['photo'] = $user-> getPhoto();
    //         $data1[$key]['type'] = $user->getRole()->getName();
    //         $data1[$key]['niveau'] = $user->getGrade()->getName();
    //         $data1[$key]['batiment'] = $user-> getStructure();
    //     }        
        
    //     $response1 = new JsonResponse();
        
    //     $response1->headers->set('Content-Type', 'application/json');
    //     $response1->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response1->setData($data1);

    //     $user2 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '2']);

    //     $data2 = array();
        
    //     foreach ($user2 as $key => $user){
    //         $data2[$key]['id'] = "id".$user->getId();
    //         $data2[$key]['fonction'] = $user->getProfession();
    //         $data2[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data2[$key]['portable'] = $user->getMobilenumber();
    //         $data2[$key]['fixe'] = $user-> getPhonenumber();
    //         $data2[$key]['etage'] = $user->getFloor();
    //         $data2[$key]['photo'] = $user-> getPhoto();
    //         $data2[$key]['type'] = $user->getRole()->getName();
    //         $data2[$key]['niveau'] = $user->getGrade()->getName();
    //         $data2[$key]['batiment'] = $user-> getStructure();
    //     }

    //     $response2 = new JsonResponse();
        
    //     $response2->headers->set('Content-Type', 'application/json');
    //     $response2->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response2->setData($data2);
        
    //     $user3 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '3']);

    //     $data3 = array();
        
    //     foreach ($user3 as $key => $user){
    //         $data3[$key]['id'] = "id".$user->getId();
    //         $data3[$key]['fonction'] = $user->getProfession();
    //         $data3[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data3[$key]['portable'] = $user->getMobilenumber();
    //         $data3[$key]['fixe'] = $user-> getPhonenumber();
    //         $data3[$key]['etage'] = $user->getFloor();
    //         $data3[$key]['photo'] = $user-> getPhoto();
    //         $data3[$key]['type'] = $user->getRole()->getName();
    //         $data3[$key]['niveau'] = $user->getGrade()->getName();
    //         $data3[$key]['batiment'] = $user-> getStructure();
    //     }

    //     $response3 = new JsonResponse();
        
    //     $response3->headers->set('Content-Type', 'application/json');
    //     $response3->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response3->setData($data3);

    //     $user4 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '4']);

    //     $data4 = array();
        
    //     foreach ($user4 as $key => $user){
    //         $data4[$key]['id'] = "id".$user->getId();
    //         $data4[$key]['fonction'] = $user->getProfession();
    //         $data4[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data4[$key]['portable'] = $user->getMobilenumber();
    //         $data4[$key]['fixe'] = $user-> getPhonenumber();
    //         $data4[$key]['etage'] = $user->getFloor();
    //         $data4[$key]['photo'] = $user-> getPhoto();
    //         $data4[$key]['type'] = $user->getRole()->getName();
    //         $data4[$key]['niveau'] = $user->getGrade()->getName();
    //         $data4[$key]['batiment'] = $user-> getStructure();
    //     }

    //     $response4 = new JsonResponse();
        
    //     $response4->headers->set('Content-Type', 'application/json');
    //     $response4->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response4->setData($data4);

    //     $user5 = $this->getDoctrine()->getRepository(User::class)->findBy(['directory' => '5']);

    //     $data5 = array();
        
    //     foreach ($user5 as $key => $user){
    //         $data5[$key]['id'] = "id".$user->getId();
    //         $data5[$key]['fonction'] = $user->getProfession();
    //         $data5[$key]['nom'] = $user->getFirstname()." ".$user->getLastname();
    //         $data5[$key]['portable'] = $user->getMobilenumber();
    //         $data5[$key]['fixe'] = $user-> getPhonenumber();
    //         $data5[$key]['etage'] = $user->getFloor();
    //         $data5[$key]['photo'] = $user-> getPhoto();
    //         $data5[$key]['type'] = $user->getRole()->getName();
    //         $data5[$key]['niveau'] = $user->getGrade()->getName();
    //         $data5[$key]['batiment'] = $user-> getStructure();
    //     }

    //     $response5 = new JsonResponse();
        
    //     $response5->headers->set('Content-Type', 'application/json');
    //     $response5->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    //     $response5->setData($data5);


    //     $files = array($response1, $response2, $response3, $response4, $response5);
    //     $zipname = 'Annuaires.zip';
    //     $zip = new ZipArchive;
        
    //     if($zip->open($zipname, ZipArchive::CREATE) == TRUE ){
    //     foreach ($files as $file) {
    //     // download file

        
    //     $numFiles = $zip->numFiles;
        
    //     dd($numFiles);
        
    //     }

    //     $zip->close();
    // }
    //     header('Content-Type: application/zip');
    //     header('Content-disposition: attachment; filename='.$zipname);
    //     header('Content-Length: ' . filesize($zipname));
    //     readfile($zipname);

    //     unlink($zipname);
        
        
    //     return $response;
    // }

    }

