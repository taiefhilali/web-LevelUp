<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Form\AdministrateurType;
use App\Repository\AdministrateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administrateur")
 */
class AdministrateurController extends AbstractController
{
    /**
     * @Route("/", name="app_administrateur_index", methods={"GET"})
     */
    public function index(AdministrateurRepository $administrateurRepository): Response
    {
        return $this->render('administrateur/index.html.twig', [
            'administrateurs' => $administrateurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_administrateur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AdministrateurRepository $administrateurRepository): Response
    {
        $administrateur = new Administrateur();
        $form = $this->createForm(AdministrateurType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $administrateurRepository->add($administrateur);
            return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administrateur/new.html.twig', [
            'administrateur' => $administrateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_administrateur_show", methods={"GET"})
     */
    public function show(Administrateur $administrateur): Response
    {
        return $this->render('administrateur/show.html.twig', [
            'administrateur' => $administrateur,
        ]);
    }

    /**
     * @Route("/{idUser}/edit", name="app_administrateur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Administrateur $administrateur, AdministrateurRepository $administrateurRepository): Response
    {
        $form = $this->createForm(AdministrateurType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $administrateurRepository->add($administrateur);
            return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administrateur/edit.html.twig', [
            'administrateur' => $administrateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_administrateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Administrateur $administrateur, AdministrateurRepository $administrateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$administrateur->getIdUser(), $request->request->get('_token'))) {
            $administrateurRepository->remove($administrateur);
        }

        return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
