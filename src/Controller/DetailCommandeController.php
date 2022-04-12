<?php

namespace App\Controller;

use App\Entity\DetailCommande;
use App\Entity\Commande;
use App\Form\DetailCommandeType;
use App\Repository\DetailCommandeRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/detail/commande")
 */
class DetailCommandeController extends AbstractController
{
    /**
     * @Route("/", name="app_detail_commande_index", methods={"GET"})
     */
    public function index(DetailCommandeRepository $detailCommandeRepository): Response
    {
        return $this->render('detail_commande/index.html.twig', [
            'detail_commandes' => $detailCommandeRepository->findAll(),
        ]);
    }

     /**
     * @Route("/{idCommande}", name="app_detail_commande_index", methods={"GET"})
     */
    public function indexx(DetailCommandeRepository $detailCommandeRepository,CommandeRepository $commande, $idCommande): Response
    {    
        $cmd = new Commande();
        $cmd = $commande->find($idCommande);
        $details = $detailCommandeRepository->findBy(['idCommande' => $cmd]);
        return $this->render('detail_commande/index.html.twig', [
            'detail_commandes' => $details,
        ]);
    }

    /**
     * @Route("/new", name="app_detail_commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DetailCommandeRepository $detailCommandeRepository): Response
    {
        $detailCommande = new DetailCommande();
        $form = $this->createForm(DetailCommandeType::class, $detailCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailCommandeRepository->add($detailCommande);
            return $this->redirectToRoute('app_detail_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('detail_commande/new.html.twig', [
            'detail_commande' => $detailCommande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idElemc}", name="app_detail_commande_show", methods={"GET"})
     */
    public function show(DetailCommande $detailCommande): Response
    {
        return $this->render('detail_commande/show.html.twig', [
            'detail_commande' => $detailCommande,
        ]);
    }

    /**
     * @Route("/{idElemc}/edit", name="app_detail_commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DetailCommande $detailCommande, DetailCommandeRepository $detailCommandeRepository): Response
    {
        $form = $this->createForm(DetailCommandeType::class, $detailCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailCommandeRepository->add($detailCommande);
            return $this->redirectToRoute('app_detail_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('detail_commande/edit.html.twig', [
            'detail_commande' => $detailCommande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idElemc}", name="app_detail_commande_delete", methods={"POST"})
     */
    public function delete(Request $request, DetailCommande $detailCommande, DetailCommandeRepository $detailCommandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detailCommande->getIdElemc(), $request->request->get('_token'))) {
            $detailCommandeRepository->remove($detailCommande);
        }

        return $this->redirectToRoute('app_detail_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
