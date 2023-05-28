<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\ClientType;
use App\Entity\ChambreHote;
use App\Entity\Image;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;


class ClientController extends AbstractController
{
    private $requestStack;
   
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    #[Route('/client', name: 'app_client')]
    public function index($chambreId= null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $client = new Client();
        $chambre = $entityManager->getRepository(Chambre::class)->find($chambreId);
        $client->setNom('amel');
        $client->setNbrPersonne(4);
        $client->setEmail('client@gmail.com');
        $client->setDateArr(new \DateTimeImmutable());
        $client->setDateDep(new \DateTimeImmutable());
        $client->setChambreHote($chambre);
        $imagePath = '/public/images/client.jpg';
        $baseUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $imageUrl = $baseUrl . '/' . $imagePath;   
        $image = new Image();
        $image->setUrl($imageUrl);
        $image->setAlt('client');
        $client->setImage($image);
        $entityManager->persist($client);
        $entityManager->flush();
        return $this->render('client/index.html.twig', [
            'id' => $client->getId(),
            'nom' => $client->getNom(),
            'nbrPersonne'=>$client->getNbrPersonne(),
            'email' => $client->getEmail(),
            'dateArr'=> $client->getDateArr()->format('Y-m-d'),
            'dateDep'=> $client->getDateDep()->format('Y-m-d'),
            'image' => $client->getImage(),
        ]);
    }
 #[Route('/client/{id}', name: 'show_client')]
    public function show($id, Request $request) 
    {    $client = $this->getDoctrine() ->getRepository(Client::class) ->find($id);
        $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/clients/';
            if (!$client) { throw $this->createNotFoundException( 'No client found for id '.$id );
         } 
     return $this->render('client/show.html.twig', ['publicPath'=>$publicPath, 'client' =>$client ]);
     }


     #[Route('/clients', name: 'find_all')]
     public function findAll(Request $request) 
     {    $entityManager = $this->getDoctrine()->getManager();
          $repo= $entityManager->getRepository(Client::class);
          $clients = $repo->findAll();
          $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/clients/';

      return $this->render('client/all.html.twig', [ 'publicPath'=>$publicPath,'clients' =>$clients ]);
      }
 

     #[Route('/ajouter', name: 'Ajouter')]
        public function ajouter(Request $request)
        { $publicPath ="uploads/clients/";
          $client = new Client();
   

            $form = $this -> createForm("App\Form\ClientType",$client);
            $form->handleRequest($request);   
            if($form->isSubmitted())
            {
            /*
            * @var UploadedFile $image
            */

      $image = $form->get('image')->getData();

    $em=$this->getDoctrine()->getManager();
    if($image){
        $imageName = $client->getId().'.'. $image->guessExtension();
        $image->move($publicPath,$imageName);
        $client->setImage($imageName);
    }
    $em->persist($client);
    $em->flush();
return $this->redirectToRoute('find_all');

}

    return $this->render('client/ajouter.html.twig',

    ['titre'=>'Add','f'=> $form->createView()]);
}
#[Route('/delete/{id}', name: 'delete_client')]
public function delete(Request $request,$id):Response
{

$j=$this->getDoctrine()->getRepository(Client::class)->find($id);
if(!$j){
    throw $this->createNotFoundException('Client not found'.$id);

}
$entityManager=$this->getDoctrine()->getManager();
$entityManager->remove($j);
$entityManager->flush();

return $this->redirectToRoute('find_all');

}
#[Route('/update/{id}', name: 'update_client', methods: ['GET', 'POST'])]

public function edit(Request $request, $id)
{ $publicPath ="uploads/clients/";
    
    $client = new Client();
$client = $this->getDoctrine()
->getRepository(Client::class)
->find($id);
if (!$client) {
throw $this->createNotFoundException(
'No client found for id '.$id
);
}
if ($client->getImage()) {
$imagePath = $this->getParameter('kernel.project_dir') . "\\public\\uploads\\clients\\" . $client->getImage();
$client->setImage(
    new File($imagePath));}
$form = $this -> createForm("App\Form\ClientType",$client);
$form->handleRequest($request);   
if($form->isSubmitted())
{
 /*
  * @var UploadedFile $image
  */

  $image = $form->get('image')->getData();

$em=$this->getDoctrine()->getManager();
if($image){
    $imageName = $client->getId().'.'. $image->guessExtension();
    $image->move($publicPath,$imageName);
    $client->setImage($imageName);
}
$em->persist($client);
$em->flush();
return $this->redirectToRoute('find_all');
}
return $this->render('client/ajouter.html.twig',
['titre'=>'Update','f' => $form->createView()] );
}


#[Route('/home', name: 'home')]
public function home(): Response
{
    $entityManager = $this->getDoctrine()->getManager();
   
    return $this->render('home.html.twig', [
        'controller_name' => 'ClientController',
    ]);
}
}
