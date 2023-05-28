<?php

namespace App\Controller;
use App\Entity\ChambreHote;
use App\Form\ChambreHoteType;
use App\Entity\Image;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\File\File;

class ChambreHoteController extends AbstractController
{
    private $requestStack;
   
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    #[Route('/chambre', name: 'app_chambre_hote')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $chambre = new ChambreHote();
        $chambre->setNomChambre('chambre1');
        $chambre->setCapacite(2);
        $chambre->setPrix(500);
        $chambre->setAdresse("Moknine");
        $imagePath = '/public/images/maisonH.jpg';
        $baseUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $imageUrl = $baseUrl . '/' . $imagePath;   
        $image = new Image();
        $image->setUrl($imageUrl);
        $image->setAlt('maison1');
        $chambre->setImage($image);

        $entityManager->persist($chambre);
        $entityManager->flush();
        $image = $chambre->getImage();
        dump($image->getUrl());

        return $this->render('chambre/index.html.twig', [
            'id' => $chambre->getId(),
            'nomChambre' => $chambre->getNomChambre(),
            'capacite' => $chambre->getCapacite(),
            'prix'=> $chambre->getPrix(),
            'adresse'=>$chambre->getAdresse(),
            'image' => $chambre->getImage(),
        ]);
    }


#[Route('/chambre/{id}', name: 'chambre_show')]
public function show($id ,  Request $request)
{ $chambre = $this->getDoctrine() ->getRepository(ChambreHote::class) ->find($id);
    
    $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/chambres/';

    $em=$this->getDoctrine()->getManager();
    $listClient =$em->getRepository(Client::class)
        ->findBy(['ChambreHote'=>$chambre]);
    if (!$chambre) { throw $this->createNotFoundException( 'No ChambreHote found for id '.$id );
    }
    return $this->render('chambre/show.html.twig', ['publicPath' => $publicPath  , 
        'chambre' => $chambre,
        'client' =>$listClient,
             ]);
}

#[Route('/chambres', name: 'find_allChambreHotes')]
public function findAll(Request $request) 
{    $entityManager = $this->getDoctrine()->getManager();
     $repo= $entityManager->getRepository(ChambreHote::class);
     $chambres = $repo->findAll();
     $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/chambres/';

 return $this->render('chambre/all.html.twig', [ 'publicPath'=>$publicPath,'chambres' =>$chambres ]);
 }


 #[Route('/ajouterChambreHote', name: 'AjouterChambreHote')]
 public function ajouter(Request $request)
 {
     $publicPath = "uploads/chambres/";
     $chambre = new ChambreHote();
 
     $form = $this->createForm(ChambreHoteType::class, $chambre);
     $form->handleRequest($request);
 
     if ($form->isSubmitted() && $form->isValid()) {
         $image = $form->get('image')->getData();
 
         $em = $this->getDoctrine()->getManager();
         
         if ($image) {
             $imageName = uniqid().'.'.$image->guessExtension();
             $image->move($publicPath, $imageName);
             $chambre->setImage($imageName);
         }
 
         $em->persist($chambre);
         $em->flush();
 
         return $this->redirectToRoute('find_allChambreHotes');
     }
 
     return $this->render('chambre/ajouter.html.twig', [
         'titre' => 'Add',
         'f' => $form->createView()
     ]);
 }
 
#[Route('/delete/{id}', name: 'delete_chambre')]
public function delete(Request $request,$id):Response
{

$g=$this->getDoctrine()->getRepository(ChambreHote::class)->find($id);
if(!$g){
throw $this->createNotFoundException('ChambreHote not found'.$id);

}
$entityManager=$this->getDoctrine()->getManager();
$listClient =$entityManager->getRepository(Client::class)->findBy(['ChambreHote'=>$g]);
foreach ($listClient as $client) {
$entityManager->remove($client);
}
$entityManager->remove($g);
$entityManager->flush();

return $this->redirectToRoute('find_allChambreHotes');

}
#[Route('/updateChambreHote/{id}', name: 'update_chambre', methods: ['GET', 'POST'])]
public function edit(Request $request, $id)
{
    $publicPath = "uploads/chambres/";
    $chambre = $this->getDoctrine()->getRepository(ChambreHote::class)->find($id);

    if (!$chambre) {
        throw $this->createNotFoundException('No ChambreHote found for id ' . $id);
    }

    // Récupérer le chemin de l'image actuelle
    $currentImagePath = $chambre->getImage();

    // Créer le formulaire en utilisant la chambre d'hôtes existante
    $form = $this->createForm(ChambreHoteType::class, $chambre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier de l'image
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            // Supprimer l'image actuelle
            if ($currentImagePath) {
                $currentImageFullPath = $this->getParameter('kernel.project_dir') . '/public/' . $publicPath . $currentImagePath;
                if (file_exists($currentImageFullPath)) {
                    unlink($currentImageFullPath);
                }
            }

            // Générer un nouveau nom de fichier unique
            $newImageName = uniqid() . '.' . $imageFile->guessExtension();

            // Déplacer le fichier vers le répertoire des images
            $imageFile->move($publicPath, $newImageName);

            // Mettre à jour le chemin de l'image dans la chambre d'hôtes
            $chambre->setImage($newImageName);
        } else {
            // Si aucun fichier n'est fourni, rétablir le chemin de l'image existante
            $chambre->setImage($currentImagePath);
        }

        // Enregistrer les modifications dans la base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($chambre);
        $em->flush();

        return $this->redirectToRoute('find_allChambreHotes');
    }

    return $this->render('chambre/ajouter.html.twig', [
        'titre' => 'Update',
        'f' => $form->createView()
    ]);
}









}

   