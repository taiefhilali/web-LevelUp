<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fournisseur")
 */
class FournisseurController extends AbstractController
{
    /**
     * @Route("/", name="app_fournisseur_index", methods={"GET"})
     */
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        return $this->render('fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_fournisseur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurRepository->add($fournisseur);
            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_fournisseur_show", methods={"GET"})
     */
    public function show(Fournisseur $fournisseur): Response
    {
        return $this->render('fournisseur/show.html.twig', [
            'fournisseur' => $fournisseur,
        ]);
    }

    /**
     * @Route("/{idUser}/edit", name="app_fournisseur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurRepository->add($fournisseur);
            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_fournisseur_delete", methods={"POST"})
     */
    public function delete(Request $request, Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getIdUser(), $request->request->get('_token'))) {
            $fournisseurRepository->remove($fournisseur);
        }

        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }
}
