<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use App\Repository\PanierElemRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,ProduitRepository $produitRepository): Response
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
     * @Route("/panier", name="app_basepanier")
     */
    public function indexpan(PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,ProduitRepository $produitRepository): Response
    {
        $pan = new Panier();
        $usr = new User();
        $usr = $user->find(1);
        $pan = $panier->findBy(['idUser' => $usr]);
        return $this->render('basepanier.html.twig', [
            'produits' => $produitRepository->findAll(),
            'panierElements' => $panierElemRepository->findBy(['idPanier' => $pan]),
        ]);
    }




}
