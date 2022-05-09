<?php

namespace App\Controller;

use App\Entity\PanierElem;
use App\Entity\Produit;
use App\Entity\Stock;
use App\Repository\StockRepository;
use App\Entity\Panier;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Entity\User;
use App\Form\PanierElemType;
use App\Repository\PanierElemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/panier/elem")
 */
class PanierElemController extends AbstractController
{
    /**
     * @Route("/", name="app_panier_elem_index", methods={"GET"})
     */
    public function index(PanierElemRepository $panierElemRepository): Response
    {
        return $this->render('panier_elem/index.html.twig', [
            'panier_elems' => $panierElemRepository->findAll(),
        ]);
    }

     /**
     * @Route("/PanierElements", name="app_panier_elements", methods={"GET"})
     */
    public function indexx(StockRepository $stock,UserRepository $userRepository,PanierElemRepository $panierElemRepository ,PanierRepository $panier,Request $request): Response
    {   
        $pan = new Panier();
        $usr = new User();
        $nbr = array();
        $usr =$userRepository->find($request->getSession()->get('id'));
        $pan = $panier->findBy(['idUser' => $usr]);
        $panElem = $panierElemRepository->findBy(['idPanier' => $pan]);
        foreach($panElem as $val){
            $stockk = $stock->findOneBy([ 'id' => $val->getId()]);
            array_push($nbr, $stockk->getQuantite());
        }
        return $this->render('panier/index.html.twig', [
            'panierElements' => $panierElemRepository->findBy(['idPanier' => $pan]) ,
            'quantite' => $nbr,
        
        ]);
    }

     /**
     * @Route("/PanierElementsJSON/{idUser}", name="PanierElementsJSON", methods={"GET"})
     */
    public function PanierElementsJSON($idUser,NormalizerInterface $Normalizer,PanierElemRepository $panierElemRepository ,PanierRepository $panier, UserRepository $user ): Response
    {   
        $pan = new Panier();
        $usr = new User();
        $nbr = array();
        $usr = $user->find($idUser);
        $pan = $panier->findBy(['idUser' => $usr]);
        $panElem = $panierElemRepository->findBy(['idPanier' => $pan]);
        
        $jsonContent = $Normalizer->normalize($panElem,'json',['groups'=>'post:read']);

       return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/PanierElementsJSONProducts/{idUser}", name="PanierElementsJSONProducts", methods={"GET"})
     */
    public function PanierElementsJSONProducts($idUser,NormalizerInterface $Normalizer,PanierElemRepository $panierElemRepository ,PanierRepository $panier, UserRepository $user ): Response
    {   
        $pan = new Panier();
        $usr = new User();
        $joint = array();
        $usr = $user->find($idUser);
        $pan = $panier->findBy(['idUser' => $usr]);
        $panElem = $panierElemRepository->findBy(['idPanier' => $pan]);
        foreach($panElem as $valeur){
            array_push($joint, $valeur->getId());
        }

        $jsonContent = $Normalizer->normalize($joint,'json',['groups'=>'post:read']);

       return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/Add/{idProduit}/{idUser}", name="app_panier_elem_new", methods={"GET", "POST"})
     */
    public function new(Request $request,UserRepository $userRepository,ProduitRepository $produit,UserRepository $user ,PanierRepository $panier,PanierElemRepository $panierElemRepository,$idProduit,$idUser): Response
    {   $pan = new Panier();
        $usr = new User();
        $pr = new Produit();
        $pr = $produit->find($idProduit);
        $usr = $userRepository->find($request->getSession()->get('id'));
        $pan = $panier->findOneBy(['idUser' => $usr]);
        $panierElem = new PanierElem();
        $panierElem->setQuantite($request->get('quantite'));
        $panierElem->setId($pr);
        $panierElem->setIdPanier($pan);
        $em = $this->getDoctrine()->getManager();
        $em->persist($panierElem);
        $em->flush();
        return $this->redirectToRoute('app_panier_elements', [], Response::HTTP_SEE_OTHER);
    
    }

      /**
     * @Route("/AddJSON/{idProduit}/{idUser}/{quantite}", name="AjoutElementJSON", methods={"GET", "POST"})
     */
    public function newJSON($quantite,NormalizerInterface $Normalizer,Request $request,ProduitRepository $produit,UserRepository $user ,PanierRepository $panier,PanierElemRepository $panierElemRepository,$idProduit,$idUser): Response
    {   $pan = new Panier();
        $usr = new User();
        $pr = new Produit();
        $pr = $produit->find($idProduit);
        $usr = $user->find($idUser);
        $pan = $panier->findOneBy(['idUser' => $usr]);
        $panierElem = new PanierElem();
        $panierElem->setQuantite($quantite);
        $panierElem->setId($pr);
        $panierElem->setIdPanier($pan);
        $em = $this->getDoctrine()->getManager();
        $em->persist($panierElem);
        $em->flush();
        $jsonContent = $Normalizer->normalize($panierElem,'json',['groups'=>'post:read']);

        return new Response("Element panier Ajoutée avec succés".json_encode($jsonContent));
    
    }


    /**
     * @Route("/{idElem}", name="app_panier_elem_show", methods={"GET"})
     */
    public function show(PanierElem $panierElem): Response
    {
        return $this->render('panier_elem/show.html.twig', [
            'panier_elem' => $panierElem,
        ]);
    }

    /**
     * @Route("/{idElem}/edit", name="app_panier_elem_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $idElem, PanierElemRepository $panierElemRepository): Response
    {
         $panierElem = new PanierElem();
         $panierElem = $panierElemRepository->find($idElem);
         $panierElem->setQuantite($request->get('quantite'));
        $panierElemRepository->add($panierElem);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('info','Mise a jour avec succés !!'); 
        return $this->redirectToRoute('app_panier_elements', [], Response::HTTP_SEE_OTHER);
    }

        /**
     * @Route("/editJSON/{idElem}", name="editJSON", methods={"GET", "POST"})
     */
    public function editJSON(NormalizerInterface $Normalizer,Request $request, $idElem, PanierElemRepository $panierElemRepository): Response
    {
         $panierElem = new PanierElem();
         $panierElem = $panierElemRepository->find($idElem);
         $panierElem->setQuantite($request->get('quantite'));
        $panierElemRepository->add($panierElem);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $Normalizer->normalize($panierElem,'json',['groups'=>'post:read']);

        return new Response("Element panier Modifiée avec succés".json_encode($jsonContent));
    }

    /**
     * @Route("/{idElem}", name="app_panier_elem_delete", methods={"POST"})
     */
    public function delete(Request $request, PanierElem $panierElem, PanierElemRepository $panierElemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierElem->getIdElem(), $request->request->get('_token'))) {
            $panierElemRepository->remove($panierElem);
        }

        return $this->redirectToRoute('app_panier_elements', [], Response::HTTP_SEE_OTHER);
    }

        /**
     * @Route("/deleteElementPanier/{idElem}", name="deleteElementPanier")
     */
    public function deleteElementPanierJSON($idElem,NormalizerInterface $Normalizer,Request $request, PanierElem $panierElem, PanierElemRepository $panierElemRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
      $panElem = $panierElemRepository->find($idElem);
      $em->remove($panElem);
      $em->flush();

      $jsonContent = $Normalizer->normalize($panElem,'json',['groups'=>'post:read']);

       return new Response("Element panier supprimée avec succés".json_encode($jsonContent));
    }
}
