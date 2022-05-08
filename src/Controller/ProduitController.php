<?php

namespace App\Controller;
use App\Repository\StockRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Produit;
use App\Entity\Stock;
use App\Entity\Panier;
use App\Repository\DetailCommandeRepository;
use App\Entity\PanierElem;
use App\Entity\User;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\PanierElemRepository;
use App\Repository\UserRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
 
   /**
     * @Route("/TopProducts", name="app_produit_Top", methods={"GET"})
     */
    public function Top( PaginatorInterface $paginator,Request $request,DetailCommandeRepository $detailCommandeRepository,PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,ProduitRepository $produitRepository): Response
    {   
        $i = 0;
        $produit = $produitRepository->findAll();
        $nbr = array();
        foreach($produit as $valeur){
        $elem = $detailCommandeRepository->findBy(['id' => $valeur]);
        foreach($elem as $val){
            $i = $i + 1 ; 
        }
        array_push($nbr, $i);
        $i = 0;
        }
        $products = $paginator->paginate(
            $produit,
            $request->query->getInt('page', 1),
            3
        );
        $pan = new Panier();
        $usr = new User();
        $usr = $user->find(1);
        $pan = $panier->findBy(['idUser' => $usr]);
        return $this->render('produit/TopProducts.html.twig', [
            'products' => $products,
            'panierElements' => $panierElemRepository->findBy(['idPanier' => $pan]),
            'nbr' => $nbr,
        ]);
    }
    /**
     * @Route("/", name="app_produit_index", methods={"GET"})
     */
    public function index( PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,ProduitRepository $produitRepository): Response
    {   
       
        $pan = new Panier();
        $usr = new User();
        $usr = $user->find(1);
        $pan = $panier->findBy(['idUser' => $usr]);
        return $this->render('base.html.twig', [
            'produits' => $produitRepository->findAll(),
            'panierElements' => $panierElemRepository->findBy(['idPanier' => $pan]),
        ]);
    }

        /**
     * @Route("/AllProducts", name="AllProducts", methods={"GET"})
     */
    public function ProductsJSON(NormalizerInterface $Normalizer,ProduitRepository $produitRepository)
    {   
       
        $produits = $produitRepository->findAll();
        $jsonContent = $Normalizer->normalize($produits,'json',['groups'=>'post:read']);

       return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProduit}", name="app_produit_show", methods={"GET"})
     */
    public function show(StockRepository $stockrepo,$idProduit,Produit $produit,PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,ProduitRepository $produitRepository): Response
    {   

        $produit = new Produit();
        $elem = new PanierElem(); 
        $test = false;
        $pan = new Panier();
        $usr = new User();
        $usr = $user->find(1);
        $produit = $produitRepository->find($idProduit);
        $stock = $stockrepo->findOneBy(['id' => $produit]);
        $pan = $panier->findBy(['idUser' => $usr]);
        $elem = $panierElemRepository->findBy(['idPanier' => $pan, 'id' => $produit]);
        if (empty($elem)){
            $test = true;
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'panierElements' => $panierElemRepository->findBy(['idPanier' => $pan]),
            'test' => $test,
            'stock' => $stock,

        ]);
    }

    /**
     * @Route("/showJSON/{idProduit}", name="JSONshow", methods={"GET"})
     */
    public function showJSON(NormalizerInterface $Normalizer,$idProduit,Produit $produit,ProduitRepository $produitRepository)
    {   

        $produit = new Produit();
        $produit = $produitRepository->find($idProduit);
        $jsonContent = $Normalizer->normalize($produit,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/{idProduit}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProduit}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getIdProduit(), $request->request->get('_token'))) {
            $produitRepository->remove($produit);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
