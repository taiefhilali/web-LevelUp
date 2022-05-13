<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="app_categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    // Affichage produit Mobile JSON
    /**
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws ExceptionInterface
     * @Route("/CategoriesList",name="CategoriesList")
     */
    public function getCategoriesJson(NormalizerInterface $normalizer){
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repo->findAll();
        $jsonCategories = $normalizer->normalize($categories,'json',['groups'=>'productsgroup']);
        return new Response(json_encode($jsonCategories));
    }
    //Fonction du suppression d'une catégorie avec JSON Mobile
    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param $id
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/deleteCategorieJson/{id}", name="deleteCategorieJson")
     */
    public function deleteCategorieJson(Request $request,NormalizerInterface $normalizer,$id){
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);
        $em->remove($categorie);
        $em->flush();
        $jsonContent =$normalizer->normalize($categorie,'json',['groups'=>'productsgroup']);
        return new Response("La catégorie a été supprimé avec succées!".json_encode($jsonContent));
    }

    /**
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @return Response
     * @throws ExceptionInterface
     * @Route("/addCategorieJson/add" , name="addCategorieJson")
     */
    public function addCategorieJson(NormalizerInterface $normalizer, Request $request
       ){
        $em=$this->getDoctrine()->getManager();
        $categorie = new Categorie();
        $categorie->setNomCategorie($request->get('nomCategorie'));
        $em->persist($categorie);
        $em->flush();
        $json_content = $normalizer->normalize($categorie, 'json',['groups'=>'productsgroup']);
        return new Response(json_encode($json_content));
    }

    /**
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @param $idCategorie
     * @return Response
     * @throws ExceptionInterface
     * @Route("/editCategorieJson/edit/{idCategorie}" , name="editCategorieJson")
     */
    public function editCategorieJson(NormalizerInterface $normalizer, Request $request, $idCategorie
    ){
        $em=$this->getDoctrine()->getManager();
        $categorie = new Categorie();
        $categorie=$em->getRepository(Categorie::class)->find($idCategorie);
        $categorie->setNomCategorie($request->get('nomCategorie'));
        $em->flush();
        $json_content = $normalizer->normalize($categorie, 'json',['groups'=>'productsgroup']);
        return new Response(json_encode($json_content));
    }






    /**
     * @Route("/new", name="app_categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategorieRepository $categorieRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie);
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCategorie}", name="app_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{idCategorie}/edit", name="app_categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie);
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCategorie}", name="app_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getIdCategorie(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie);
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
