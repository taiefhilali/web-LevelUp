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
    public function indexx(StockRepository $stock,PanierElemRepository $panierElemRepository ,PanierRepository $panier, UserRepository $user ): Response
    {   
        $pan = new Panier();
        $usr = new User();
        $nbr = array();
        $usr = $user->find(1);
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
     * @Route("/Add/{idProduit}/{idUser}", name="app_panier_elem_new", methods={"GET", "POST"})
     */
    public function new(Request $request,ProduitRepository $produit,UserRepository $user ,PanierRepository $panier,PanierElemRepository $panierElemRepository,$idProduit,$idUser): Response
    {   $pan = new Panier();
        $usr = new User();
        $pr = new Produit();
        $pr = $produit->find($idProduit);
        $usr = $user->find($idUser);
        $pan = $panier->findOneBy(['idUser' => $usr]);
        $panierElem = new PanierElem();
        $panierElem->setQuantite($request->get('quantite'));
        $panierElem->setId($pr);
        $panierElem->setIdPanier($pan);
        $em = $this->getDoctrine()->getManager();
        $em->persist($panierElem);
        $em->flush();
        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    
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
     * @Route("/{idElem}", name="app_panier_elem_delete", methods={"POST"})
     */
    public function delete(Request $request, PanierElem $panierElem, PanierElemRepository $panierElemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierElem->getIdElem(), $request->request->get('_token'))) {
            $panierElemRepository->remove($panierElem);
        }

        return $this->redirectToRoute('app_panier_elements', [], Response::HTTP_SEE_OTHER);
    }
}
