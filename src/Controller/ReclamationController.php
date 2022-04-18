<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{
    /**
     * @Route("/", name="app_reclamation_index", methods={"GET"})
     */
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/indexFront", name="app_reclamation_indexFront", methods={"GET"})
     */
    public function indexFront(ReclamationRepository $reclamationRepository): Response
    { $CLIENTID=200;
        $RecUser=$reclamationRepository->findBy(['idUser'=>$CLIENTID]);


        return $this->render('reclamation/indexFront.html.twig', [
            'reclamations' => $RecUser,
        ]);
    }


    /**
     * @Route("/new", name="app_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {$CLIENTID=200;
        $User=$this->getDoctrine()->getRepository(User::class)->find($CLIENTID);
        $reclamation = new Reclamation();
        $reclamation->setIdUser($User);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->add($reclamation);
            return $this->redirectToRoute('app_reclamation_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idReclamation}", name="app_reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }


    /**
     * @Route("/{idReclamation}/edit", name="app_reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->add($reclamation);
            return $this->redirectToRoute('app_reclamation_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idReclamation}", name="app_reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdReclamation(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation);
        }

        return $this->redirectToRoute('app_reclamation_indexFront', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/deleteBack/{idReclamation}", name="app_reclamation_delete_back", methods={"POST"})
     */
    public function deleteBack(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdReclamation(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/warnReclamation/{idReclamation}", name="warn_reclamation")
     */
    public function warn_reclamation(Request $request,$idReclamation,MailerInterface $mailer): Response
    {
        $reclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($idReclamation);
        $reclamation->setWarn(true);
        $idlivreur=$reclamation->getIdLivraison()->getIdUser();
        $livreur=$this->getDoctrine()->getRepository(User::class)->find($idlivreur);

         $this->getDoctrine()->getManager()->flush();
        $email=(new Email())
            ->from('amal.nouira26@gmail.com')
            ->to($livreur->getEmail())
            ->subject('Warning ')
            ->text($reclamation->getDescription());
        $mailer->send($email);
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);

    }
}
