<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Margin\Margin;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
//use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\QrCode;


/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_produit_index", methods={"GET"})
     */
    public function index(Request $request,ProduitRepository $produitRepository,PaginatorInterface $paginator): Response
    {
        // AFFICHAGE INDEX DANS LE BACK-END
        $donnees=$produitRepository->findAll(); //select *
        $donnees_rev=array_reverse($donnees);
        $produit= $paginator->paginate(
            $donnees_rev,
            $request->query->getInt('page',1),4

        );
        return $this->render('produit/index.html.twig',['produits'=>$produit]);
//        return $this->render('produit/index.html.twig', [
//            'produits' => $produitRepository->findAll(),
//        ]

    }
    /**
     * @return void
     * @Route("/qrCode", name="qr_function")
     */
    public function genQrCode(ProduitRepository $repo, BuilderInterface $builder){
//    code QR
        $this->builder = $builder;
        $url = 'https://www.google.com/search?q=';

        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');
    $query='SELECT * from Produit';
        $path = dirname(__DIR__, 2).'/public/assets/';

        // set qrcode
        $result = $this->builder
            ->data($query)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->labelText($dateString)
            ->labelAlignment(new LabelAlignmentCenter())
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->build()
        ;

        //generate name
        $namePng = uniqid('', '') . '.png';

        //Save img png
        $result->saveToFile($path.'qr-code/'.$namePng);
    $result->getDataUri();
        return $this->render('produit/qrtemp.html.twig',['produits'=>$result]);


    }
    /**
     * @Route("/stats", name="stats")
     */
    public function stat(ProduitRepository $prodrepo){
        $produits = $prodrepo->findAll();
        $categorieType= [];
        $prodNumber = [];


        foreach ($produits as $produit){
//            $categorieType[] = $produit->getIdCategorie()->getNomCategorie();

            $categorieType[] = $produit->getNom();

//           $pr= $produit->getIdCategorie();
//           $pr1=count($pr);
            $prodNumber[] = $produit->getPrixFinal();
        }
        return $this->render('produit/stats.html.twig',[
            'categorieType' =>json_encode($categorieType),
            'prodNumber'=>json_encode($prodNumber)
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route ("/generateExcel", name="excel")
     */
    public function generateExcel(ProduitRepository $prodrepo){
        $produit = new Produit();
        $produits = $prodrepo->findAll();
        $spreadsheet = new Spreadsheet();
//        for
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', '$produit->getPrixFinal()');
        $sheet->setCellValue('A3', 'Hello World !');
        $sheet->setCellValue();
        $sheet->setCellValue('A4', 'Hello World !');
        $sheet->setCellValue('A5', 'Hello World !');
        $sheet->setCellValue('A6', 'Hello World !');
        $sheet->setCellValue('A7', 'Hello World !');

        $sheet->setTitle("Base de données des Produits");

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'my_first_excel_symfony4.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }


    // METHODE DE RECHECHE DANS LE BACK-OFFICE
    /**
     * @Route("/Recherche", name="Recherche")
     * @param ProduitRepository $repository
     * @param Request $request
     * @return Response
     */

    function Recherche(ProduitRepository $repository,Request $request,PaginatorInterface $paginator){
        $nom=$request->get('nom');
        $Produit=$repository->findBy(['nom'=>$nom]);
//        $donnees=$ProduitRepository->findAll(); //select *
        $donnees_rev=array_reverse($Produit);
        $produit= $paginator->paginate(
            $donnees_rev,
            $request->query->getInt('page',1),4);
        return $this->render('produit/index.html.twig',['produits'=>$Produit]);

    }


    // AFFICHAGE FRONT - OFFICE INDEX
    /**
     * @Route("/productFront", name="app_produit_index_front", methods={"GET"})
     */

    public function indexFront(ProduitRepository $produitRepository, PaginatorInterface  $paginator, Request  $request): Response
    {
        $produits=$this->sort($produitRepository);
        $products = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('produit/indexFront.html.twig', [
            'products' => $products,]

        );
    }
    /**
     * @param ProduitRepository $repo
     * @return Produit[]
     */
    //FONCTRION TRI PAR PRIX
    function sort(ProduitRepository $repo){
        $produit=$repo->findBy(array(), array('prix' => 'ASC')); // A METTRE PRIX FINAL.
        return $produit;
    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'info',
                'Ajout avec succés!'
            );
            $prix = $produit->getPrix();
            $promo = $produit->getPromotion();
            if ($promo == 0){
            $produit->setPrixFinal($prix);
            }
            else{
                $produit->setPrixFinal($prix - (($prix * $promo)/ 100));
            }
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProduit}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }



    /**
     * @Route("/{idProduit}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'info',
                'Mise à jour du Produit avec succés!'
            );
            $prix = $produit->getPrix();
            $promo = $produit->getPromotion();
            if ($promo == 0){
                $produit->setPrixFinal($prix);
            }
            else{
                $produit->setPrixFinal($prix - (($prix * $promo)/ 100));
            }
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{idProduit}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getIdProduit(), $request->request->get('_token'))) {
            $produitRepository->remove($produit);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }





//    public function countNumberProducts()
//    {
//       // requete SQL
//        $entityManager=$this->getEntityManger();
//        $query = $entityManager->createQuery('SELECT categorie.nom_categorie, COUNT(categorie.nom_categorie) as nbr_produits FROM categorie, produit WHERE (categorie.id_categorie = produit.id_categorie) GROUP BY categorie.nom_categorie');
//        $req=$query->getResult();
//    }
}

