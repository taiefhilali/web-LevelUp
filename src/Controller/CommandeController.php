<?php

namespace App\Controller;
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
use App\Repository\PanierElemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/commandes", name="app_conde_index", methods={"GET"})
     */
    public function indexx(Request $request,PaginatorInterface $paginator,PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,CommandeRepository $commandeRepository): Response
    {
        $pan = new Panier();
        $usr = new User();
        $usr = $user->find(1);
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
     * @Route("/Add/{idUser}/{prixProduits}/{Livraison}/{prixTotal}", name="app_commande_new", methods={"GET", "POST"})
     */
    public function new( \Swift_Mailer $mailer ,Request $request,$idUser,$prixProduits,
    PanierElemRepository $panElem,PanierRepository $panier,DetailCommandeRepository $detail
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
        $commande->setPrixTotal($prixTotal);
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
    $pdfOptions->set('defaultFont', 'Arial');
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
    $message = (new \Swift_Message('Nouveau Contact'))
    ->setFrom('hazembayoudh886@gmail.com')
    ->setTo('hazembayoudh886@gmail.com')
    ->setsubject('Votre Facture !!');
    $attachement = new \Swift_Attachment($output, "Facture.pdf", 'application/pdf' );
    $message->attach($attachement);

        $mailer->send($message);        
        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        
    }

    /**
     * @Route("/{idCommande}", name="app_commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
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
}
