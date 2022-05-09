<?php

namespace App\Controller;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Repository\DetailCommandeRepository;
use App\Entity\User;
use App\Entity\Panier;
use App\Entity\PanierElem;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use Symfony\Component\Mime\Email;
use App\Repository\PanierElemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="app_commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

      /**
     * @Route("/AllCommandes", name="AllCommandes",methods={"GET"})
     */
    public function AllCommandesJSON(NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Commande :: class);
        $commandes = $repository->findAll();
        $jsonContent = $Normalizer->normalize($commandes,'json',['groups'=>'post:read']);

       return new Response(json_encode($jsonContent));
    }

   /**
     * @Route("/commandes", name="app_conde_index", methods={"GET"})
     */
    public function indexx(Request $request,PaginatorInterface $paginator,PanierRepository $panier,UserRepository $userRepository,PanierElemRepository $panierElemRepository,CommandeRepository $commandeRepository): Response
    {
        $pan = new Panier();
        $usr = new User();
        $usr =$userRepository->find($request->getSession()->get('id'));
        $cmd = $commandeRepository->findAll();
        $pan = $panier->findBy(['idUser' => $usr]);
        $commande = $paginator->paginate(
            $cmd,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('commande/clientCommande.html.twig', [
            'commandes' => $commande,
            'panierElements' => $panierElemRepository->findBy(['idPanier' => $pan]),
        ]);
    }

     /**
     * @Route("/ClientCommandesJSON/{idUser}", name="ClientCommandesJSON", methods={"GET"})
     */
    public function ClientCommandesJSON(NormalizerInterface $Normalizer,$idUser,Request $request,PaginatorInterface $paginator,PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,CommandeRepository $commandeRepository): Response
    {
        $pan = new Panier();
        $usr = new User();
        $usr = $user->find($idUser);
        $cmd = $commandeRepository->findBy(['idUser' => $usr]);
        $jsonContent = $Normalizer->normalize($cmd,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
        
    }

    /**
     * @Route("/payer/{idUser}/{prixProduits}/{Livraison}/{prixTotal}/{mode}", name="app_comm_payer")
     */
    public function inde(MailerInterface $mailer ,UserRepository $userRepository,Request $request,$prixTotal,$idUser,$prixProduits,$Livraison,$mode
        ,PanierElemRepository $panElem,PanierRepository $panier,DetailCommandeRepository $detail
        ,CommandeRepository $commandeRepository): Response
    {
        $pan = new Panier();
        $cmd = new Commande();
        $Detailcmd = new DetailCommande();
        $usr = new User();
        $usr =$userRepository->find($request->getSession()->get('id'));
        $pan = $panier->findOneBy(['idUser'=> $usr]);
        $panierElements = $panElem->findBy(['idPanier' => $pan]);


        $commande = new Commande();
        $commande->setIdUser($usr);
        $commande->setDateCommande(new \DateTime());
        $commande->setPrixProduits($prixProduits);
        $commande->setPrixLivraison($Livraison);
        $commande->setLatitude($request->get('lattt'));
        $commande->setLongitude($request->get('lonnn'));
        $commande->setPrixTotal($prixTotal);
        $commande->setMode($mode);
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $cmd = $commandeRepository->findOneBy(['idCommande'=> $commande->getIdCommande()]);

        foreach($panierElements as $Elem)
        {   $Detailcmd = new DetailCommande();
            $Detailcmd->setIdCommande($cmd);
            $Detailcmd->setQuantite($Elem->getQuantite());
            $Detailcmd->setId($Elem->getId());
            $em->persist($Detailcmd);
        }
        $em->flush();
        foreach($panierElements as $Elem)
        {
            $em->remove($Elem);
        }
        $em->flush();


        $pdfOptions = new Options();

        $dompdf = new Dompdf($pdfOptions);
        $panierEle = $detail->findBy(['idCommande' => $cmd]);
        $html = $this->renderView('commande/mypdf.html.twig', [
            'user' => $usr ,
            'panierElements' => $panierEle,
            'cmd' => $cmd,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $output = $dompdf->output();

        $message = ( new Email())
            ->from('lup634771@gmail.com')
            ->to('hazembayoudh886@gmail.com')
            ->subject('Votre Facture !!')
            ->text('Sending emails is fun again!')
            ->attach($output, "Facture.pdf", 'application/pdf');
        $mailer->send($message);
        Stripe::setApiKey('sk_test_51Ks89UCXTqtJcSxPSlNzU1YoSmb3jNW4ja2I6xw9nH4vVzQ3u4ACnJQ8sUr5jQODs5ce9OH8Ys7VFnoSkjRv5xDB00frb9D2f3');
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount'  => $prixTotal*100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }

        /**
     * @Route("/success-url", name="success_url")
     */
    public function successUrl(): Response
    {
        return $this->render('panier/Success.html.twig', []);
    }

         /**
     * @Route("/cancel-url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        return $this->render('panier/cancel.html.twig', []);
    }
    /**
     * @Route("/Add/{idUser}/{prixProduits}/{Livraison}/{prixTotal}/{mode}", name="app_commande_new", methods={"GET", "POST"})
     */
    public function new( \Swift_Mailer $mailer ,Request $request,$idUser,$prixProduits,
                                       $mode,PanierElemRepository $panElem,PanierRepository $panier,DetailCommandeRepository $detail
        ,$Livraison,$prixTotal ,CommandeRepository $commandeRepository ,UserRepository $userRepository,
                         MailerInterface $mailerd): Response
    {

        $pan = new Panier();
        $cmd = new Commande();
        $Detailcmd = new DetailCommande();
        $usr = new User();
        $usr =$userRepository->find($request->getSession()->get('id'));
        $pan = $panier->findOneBy(['idUser'=> $usr]);
        $panierElements = $panElem->findBy(['idPanier' => $pan]);


        $commande = new Commande();
        $commande->setIdUser($usr);
        $commande->setDateCommande(new \DateTime());
        $commande->setPrixProduits($prixProduits);
        $commande->setPrixLivraison($Livraison);
        $commande->setLatitude($request->get('latt'));
        $commande->setLongitude($request->get('lonn'));
        $commande->setPrixTotal($prixTotal);
        $commande->setMode($mode);
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $cmd = $commandeRepository->findOneBy(['idCommande'=> $commande->getIdCommande()]);

        foreach($panierElements as $Elem)
        {   $Detailcmd = new DetailCommande();
            $Detailcmd->setIdCommande($cmd);
            $Detailcmd->setQuantite($Elem->getQuantite());
            $Detailcmd->setId($Elem->getId());
            $em->persist($Detailcmd);
        }
        $em->flush();
        foreach($panierElements as $Elem)
        {
            $em->remove($Elem);
        }
        $em->flush();


        $pdfOptions = new Options();

        $dompdf = new Dompdf($pdfOptions);
        $panierEle = $detail->findBy(['idCommande' => $cmd]);
        $html = $this->renderView('commande/mypdf.html.twig', [
            'user' => $usr ,
            'panierElements' => $panierEle,
            'cmd' => $cmd,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $output = $dompdf->output();
        $message = ( new Email())
            ->from('lup634771@gmail.com')
            ->to('hazembayoudh886@gmail.com')
            ->subject('Votre Facture !!')
            ->text('Sending emails is fun again!')
            ->attach($output, "Facture.pdf", 'application/pdf');
        $mailerd->send($message);
        return $this->redirectToRoute('app_produit_index_front', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/AddCmdJSON/{idUser}/{prixProduits}/{Livraison}/{prixTotal}/{mode}", name="NewCmdJSON", methods={"GET", "POST"})
     */
    public function newJSON( NormalizerInterface $Normalizer,\Swift_Mailer $mailer ,Request $request,$idUser,$prixProduits,
    $mode,PanierElemRepository $panElem,PanierRepository $panier,DetailCommandeRepository $detail
    ,$Livraison,$prixTotal ,CommandeRepository $commandeRepository ,UserRepository $user): Response
    {   
        
        $pan = new Panier();
        $cmd = new Commande();
        $Detailcmd = new DetailCommande();
        $usr = new User();
        $usr = $user->find($idUser);
        $pan = $panier->findOneBy(['idUser'=> $usr]);
        $panierElements = $panElem->findBy(['idPanier' => $pan]);
        
        $commande = new Commande();
        $commande->setIdUser($usr);
        $commande->setDateCommande(new \DateTime());
        $commande->setPrixProduits($prixProduits);
        $commande->setPrixLivraison($Livraison);
        $commande->setLatitude($request->get('latt'));
        $commande->setLongitude($request->get('lonn'));
        $commande->setPrixTotal($prixTotal);
        $commande->setMode($mode);
        $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            $cmd = $commandeRepository->findOneBy(['idCommande'=> $commande->getIdCommande()]);
             
    foreach($panierElements as $Elem)
    {   $Detailcmd = new DetailCommande();
        $Detailcmd->setIdCommande($cmd);
        $Detailcmd->setQuantite($Elem->getQuantite());
        $Detailcmd->setId($Elem->getId());
        $em->persist($Detailcmd);
    }
    $em->flush();
    foreach($panierElements as $Elem)
    {
        $em->remove($Elem);
    }
    $em->flush();
        
        $jsonContent = $Normalizer->normalize($commande,'json',['groups'=>'post:read']);

       return new Response("Commande Ajoutée avec succés".json_encode($jsonContent));
    }





    /**
     * @Route("/{idCommande}", name="app_commande_show", methods={"GET"})
     */
    public function show($idCommande,Commande $commande,CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->find($idCommande);
        return $this->render('commande/map.html.twig', [
            'commande' => $commande,
        ]);
    }
    
    /**
     * @Route("/{idCommande}/edit", name="app_commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->add($commande);
            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    } 
       /**
     * @Route("/{idCommande}", name="app_commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getIdCommande(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande);
        }

        return $this->redirectToRoute('app_conde_index',[], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/deleteCommande/{idCommande}", name="deleteCmdJSON")
     */
    public function deleteJSON(NormalizerInterface $Normalizer,Request $request,$idCommande, CommandeRepository $commandeRepository)
    {
      $em = $this->getDoctrine()->getManager();
      $commande = $commandeRepository->find($idCommande);
      $em->remove($commande);
      $em->flush();

      $jsonContent = $Normalizer->normalize($commande,'json',['groups'=>'post:read']);

       return new Response("Commande supprimée avec succés".json_encode($jsonContent));
    }
}
