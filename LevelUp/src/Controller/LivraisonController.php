<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Commande;
use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\CommandeRepository;
use App\Repository\LivraisonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/livraison")
 */
class LivraisonController extends AbstractController
{
    /**
     * @Route("/", name="app_livraison_index", methods={"GET"})
     */
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="app_livraison_new", methods={"GET","POST"})
     */
    public function new(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $livraison->setEtatLivraison("en cours");
            $livraisonRepository->add($livraison);
            return $this->redirectToRoute('app_calendar_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws ExceptionInterface
     * @Route("/AllLivraisons",name="AllLivraisosns")
     */
    public function AllLivraisons(NormalizerInterface $normalizer){
        $repo = $this->getDoctrine()->getRepository(Livraison::class);
        $livraisons = $repo->findAll();
        $jsonLivraisons = $normalizer->normalize($livraisons,'json',['groups'=>'reclamations']);
        return new Response(json_encode($jsonLivraisons));
    }
    /**
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @param UserRepository $usr
     * @param CommandeRepository $Com
     * @param $idUser
     * @param $idCommande
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/addLivraisonJson/add/{idUser}/{idCommande}" , name="addLivraisonjson")
     */

    public function addLivraisonJson(NormalizerInterface $normalizer, Request $request, UserRepository $usr, CommandeRepository $Com
        ,$idUser,$idCommande){
        $em=$this->getDoctrine()->getManager();
        $livraison = new Livraison();
        $user = new User();
        $user = $usr->find($idUser);
        $commande = new Commande();
        $commande= $Com->find($idCommande);
        $livraison->setDate($request->get('date'));
        $livraison->setEtatLivraison($request->get('etatlivraison'));

        $livraison->setIdUser($user);
        $livraison->setIdCommande($commande);
        $em->persist($livraison);
        $em->flush();
        $json_content = $normalizer->normalize($livraison, 'json',['groups'=>'reclamations']);
        return new Response(json_encode($json_content));
    }
    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param $id
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/deleteLivraisonJson/{id}", name="deleteLivraisonJson")
     */
    public function deleteLivraisonJson(Request $request,NormalizerInterface $normalizer,$id){
        $em = $this->getDoctrine()->getManager();
        $livraisons = $em->getRepository(Livraison::class)->find($id);
        $em->remove( $livraisons);
        $em->flush();
        $jsonContent =$normalizer->normalize( $livraisons,'json',['groups'=>'reclamations']);
        return new Response("livraison supprimée avec succée".json_encode($jsonContent));

    }




    /**
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @return Response
     * @throws ExceptionInterface
     * @Route("/editLivraisontJson/edit/{idLivraison}/{idUser}/{idCommande}" , name="addLivraisonjson")
     */
    public function editLivraisontJson(NormalizerInterface $normalizer, Request $request, UserRepository $usr, CommandeRepository $Com
        ,$idUser,$idCommande,$idLivraison){
        $em=$this->getDoctrine()->getManager();
        $livraison = new Livraison();
        $user = new User();
        $user = $usr->find($idUser);
        $commande = new Commande();
        $commande= $Com->find($idCommande);
        $livraison=$em->getRepository(Livraison::class)->find($idLivraison);
        $livraison->setDate($request->get('date'));
        $livraison->setEtatLivraison($request->get('etatlivraison'));

        $livraison->setIdUser($user);
        $livraison->setIdCommande($commande);

        $em->flush();
        $json_content = $normalizer->normalize($livraison, 'json',['groups'=>'reclamations']);
        return new Response(json_encode($json_content));
    }

    /**
     * @Route("/indexBack", name="app_livraison_indexBack", methods={"GET"})
     */
    public function indexBack(LivraisonRepository $livraisonRepository): Response
    {
        $LivId=3;
        $LivUser=$livraisonRepository->findBy(['idUser'=>$LivId]);
        return $this->render('livraison/indexBack.html.twig', [
            'livraisons' =>$LivUser,
        ]);
    }


    /**
     * @Route("/new", name="app_livraison_newBack", methods={"GET", "POST"})
     */
    public function newBack(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraison->setEtatLivraison("en cours");
            $livraisonRepository->add($livraison);
            return $this->redirectToRoute('app_livraison_indexBack', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{idLivraison}", name="app_livraison_show", methods={"GET"})
     */
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    /**
     * @Route("/{idLivraison}/edit", name="app_livraison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash(
                'info',
                'Modification avec succés!');

            $livraisonRepository->add($livraison);
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idLivraison}", name="app_livraison_delete", methods={"POST"})
     */
    public function delete(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getIdLivraison(), $request->request->get('_token'))) {
            $livraisonRepository->remove($livraison);
        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
}
