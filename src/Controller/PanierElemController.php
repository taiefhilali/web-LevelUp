<?php

namespace App\Controller;

use App\Entity\PanierElem;
use App\Form\PanierElemType;
use App\Repository\PanierElemRepository;
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
     * @Route("/new", name="app_panier_elem_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PanierElemRepository $panierElemRepository): Response
    {
        $panierElem = new PanierElem();
        $form = $this->createForm(PanierElemType::class, $panierElem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panierElemRepository->add($panierElem);
            return $this->redirectToRoute('app_panier_elem_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier_elem/new.html.twig', [
            'panier_elem' => $panierElem,
            'form' => $form->createView(),
        ]);
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
    public function edit(Request $request, PanierElem $panierElem, PanierElemRepository $panierElemRepository): Response
    {
        $form = $this->createForm(PanierElemType::class, $panierElem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panierElemRepository->add($panierElem);
            return $this->redirectToRoute('app_panier_elem_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier_elem/edit.html.twig', [
            'panier_elem' => $panierElem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idElem}", name="app_panier_elem_delete", methods={"POST"})
     */
    public function delete(Request $request, PanierElem $panierElem, PanierElemRepository $panierElemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierElem->getIdElem(), $request->request->get('_token'))) {
            $panierElemRepository->remove($panierElem);
        }

        return $this->redirectToRoute('app_panier_elem_index', [], Response::HTTP_SEE_OTHER);
    }
}
