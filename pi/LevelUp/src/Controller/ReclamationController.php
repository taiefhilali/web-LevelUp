<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Repository\LivraisonRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


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
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws ExceptionInterface
     * @Route("/AllReclamations",name="AllReclamations")
     */
    public function AllReclamations(NormalizerInterface $normalizer){
        $repo = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamations = $repo->findAll();
        $jsonReclamations = $normalizer->normalize($reclamations,'json',['groups'=>'reclamations']);
        return new Response(json_encode($jsonReclamations));
    }


    /**
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @return Response
     * @throws ExceptionInterface
     * @Route("/addReclamationtJson/add/{idUser}/{idLivraison}" , name="addReclamationjson")
     */
    public function addReclamationtJson(NormalizerInterface $normalizer, Request $request, UserRepository $usr, LivraisonRepository $Liv
        ,$idUser,$idLivraison){
        $em=$this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $user = new User();
        $user = $usr->find($idUser);
        $livraison = new Livraison();
        $livraison = $Liv->find($idLivraison);
        $reclamation->setDescription($request->get('description'));
        $reclamation->setWarn($request->get('warn'));

        $reclamation->setIdUser($user);
        $reclamation->setIdLivraison($livraison);
        $em->persist($reclamation);
        $em->flush();
        $json_content = $normalizer->normalize($reclamation, 'json',['groups'=>'reclamations']);
        return new Response(json_encode($json_content));
    }
    /**
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @return Response
     * @throws ExceptionInterface
     * @Route("/editReclamationtJson/edit/{idReclamation}/{idUser}/{idLivraison}" , name="editReclamationjson")
     */
    public function editReclamationtJson(NormalizerInterface $normalizer, Request $request, UserRepository $usr, LivraisonRepository $Liv
        ,$idUser,$idLivraison,$idReclamation){
        $em=$this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $user = new User();
        $user = $usr->find($idUser);
        $livraison = new Livraison();
        $livraison = $Liv->find($idLivraison);
        $reclamation=$em->getRepository(Reclamation::class)->find($idReclamation);
        $reclamation->setDescription($request->get('description'));
        $reclamation->setWarn($request->get('warn'));

        $reclamation->setIdUser($user);
        $reclamation->setIdLivraison($livraison);

        $em->flush();
        $json_content = $normalizer->normalize($reclamation, 'json',['groups'=>'reclamations']);
        return new Response(json_encode($json_content));
    }

    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param $id
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/deleteReclamationJson/{id}", name="deleteReclmationJson")
     */
    public function deleteReclamationJson(Request $request,NormalizerInterface $normalizer,$id){
        $em = $this->getDoctrine()->getManager();
        $reclamations = $em->getRepository(Reclamation::class)->find($id);
        $em->remove( $reclamations);
        $em->flush();
        $jsonContent =$normalizer->normalize( $reclamations,'json',['groups'=>'productsgroup']);
        return new Response("reclamation supprimée avec succée".json_encode($jsonContent));

    }






    /**
     * @Route("/indexFront", name="app_reclamation_indexFront", methods={"GET"})
     */
    public function indexFront(ReclamationRepository $reclamationRepository ,UserRepository $userRepository,Request $request, PaginatorInterface $paginator): Response
    { $CLIENTID=$userRepository->find($request->getSession()->get('id'));
        $RecUser=$reclamationRepository->findBy(['idUser'=>$CLIENTID]);
        $reclamations = $paginator->paginate(
            $RecUser ,
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('reclamation/indexFront.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }


    /**
     * @Route("/new", name="app_reclamation_new", methods={"GET","POST"})
     */
    public function new(Request $request, ReclamationRepository $reclamationRepository,UserRepository $userRepository): Response
    {$CLIENTID=$userRepository->find($request->getSession()->get('id'));
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
            $this->addFlash('success', 'VOTRE RECLAMATION EST MODIFIEE AVEC SUCCESS');

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
    public function warn_reclamation(Request $request,$idReclamation,MailerInterface $mailer, FlashyNotifier $flashy ): Response
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
        $flashy->success('WARNING!', 'http://your-awesome-link.com');

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);

    }
}
