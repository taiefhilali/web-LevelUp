<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Categorie;
use App\Entity\Panier;
use App\Entity\PanierElem;
use App\Entity\User;
use App\Form\SearchFormType;
use App\Repository\CategorieRepository;
use App\Repository\DetailCommandeRepository;
use App\Repository\PanierElemRepository;
use App\Repository\PanierRepository;
use App\Repository\StockRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Picqer\Barcode\BarcodeGeneratorHTML;
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
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{    // AFFICHAGE FRONT - OFFICE INDEX
    /**
     * @Route("/productFront", name="app_produit_index_front", methods={"GET"})
     */

    public function indexFront(ProduitRepository $produitRepository, PaginatorInterface  $paginator, Request  $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class,$data);
        $form->handleRequest($request);
//      $produits=$this->sort($produitRepository);
        $produits=$produitRepository ->findSearch($data);
        $products = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('produit/indexFront.html.twig', [
                'products' => $products, 'form'=>$form->createView()]

        );
    }


    /**
     * @Route("/TopProducts", name="app_produit_Top", methods={"GET"})
     */
    public function Top( PaginatorInterface $paginator,Request $request,DetailCommandeRepository $detailCommandeRepository,PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,ProduitRepository $produitRepository): Response
    {
        $i = 0;
        $produit = $produitRepository->findAll();
        $nbr = array();
        foreach($produit as $valeur){
            $elem = $detailCommandeRepository->findBy(['id' => $valeur]);
            foreach($elem as $val){
                $i = $i + 1 ;
            }
            array_push($nbr, $i);
            $i = 0;
        }
        $products = $paginator->paginate(
            $produit,
            $request->query->getInt('page', 1),
            3
        );
        $pan = new Panier();
        $usr = new User();
        $usr = $user->find(1);
        $pan = $panier->findBy(['idUser' => $usr]);
        return $this->render('produit/TopProducts.html.twig', [
            'products' => $products,
            'panierElements' => $panierElemRepository->findBy(['idPanier' => $pan]),
            'nbr' => $nbr,
        ]);
    }


    /**
     * @Route("/{idProduit}", name="app_produit_showpanier", methods={"GET"})
     */
    public function showpanier(StockRepository $stockrepo,$idProduit,Produit $produit,PanierRepository $panier,UserRepository $user,PanierElemRepository $panierElemRepository,ProduitRepository $produitRepository): Response
    {

        $produit = new Produit();
        $elem = new PanierElem();
        $test = false;
        $pan = new Panier();
        $usr = new User();
        $usr = $user->find(1);
        $produit = $produitRepository->find($idProduit);
        $stock = $stockrepo->findOneBy(['id' => $produit]);
        $pan = $panier->findBy(['idUser' => $usr]);
        $elem = $panierElemRepository->findBy(['idPanier' => $pan, 'id' => $produit]);
        if (empty($elem)){
            $test = true;
        }
        return $this->render('produit/showfront.html.twig', [
            'produit' => $produit,
            'panierElements' => $panierElemRepository->findBy(['idPanier' => $pan]),
            'test' => $test,
            'stock' => $stock,

        ]);
    }
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
    }

    //Fonction du suppression d'un Produit avec JSON Mobile
    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param $id
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/deleteProductJson/{id}", name="deleteProductJson")
     */
    public function deleteProductJson(Request $request,NormalizerInterface $normalizer,$id){
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);
        $em->remove($produit);
        $em->flush();
        $jsonContent =$normalizer->normalize($produit,'json',['groups'=>'productsgroup']);
        return new Response("Le produit a été supprimé avec succées!".json_encode($jsonContent));
    }
    // Affichage produit Mobile JSON
    /**
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws ExceptionInterface
     * @Route("/ProductsList",name="ProductsList")
     */
    public function getProductsJson(NormalizerInterface $normalizer){
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repo->findAll();
        $jsonProduits = $normalizer->normalize($produits,'json',['groups'=>'productsgroup']);
        return new Response(json_encode($jsonProduits));
    }

    /**
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @return Response
     * @throws ExceptionInterface
     * @Route("/addProduitJson/add/{idUser}/{idCategorie}" , name="addProduitjson")
     */
    public function addProduitJson(NormalizerInterface $normalizer, Request $request, UserRepository $usr, CategorieRepository $cat
    ,$idUser,$idCategorie){
        $em=$this->getDoctrine()->getManager();
        $produit = new Produit();
        $user = new User();
        $user = $usr->find($idUser);
        $categorie = new Categorie();
        $categorie = $cat->find($idCategorie);
        $produit->setNom($request->get('nom'));
        $produit->setReference($request->get('reference'));
        $produit->setPrix($request->get('prix'));
        $produit->setDescription($request->get('description'));
        $produit->setPromotion($request->get('promotion'));
        $produit->setImage($request->get('image'));
        $produit->setPrixFinal($request->get('prixFinal'));
        $produit->setIdUser($user);
        $produit->setIdCategorie($categorie);
        $em->persist($produit);
        $em->flush();
        $json_content = $normalizer->normalize($produit, 'json',['groups'=>'productsgroup']);
        return new Response(json_encode($json_content));
    }
// Fonction Modification json mobile

    /**
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @param UserRepository $usr
     * @param CategorieRepository $cat
     * @param $idUser
     * @param $idCategorie
     * @return Response
     * @throws ExceptionInterface
     * @Route("/modifierProduitJson/modifier/{idProduit}/{idUser}/{idCategorie}" , name="modifierProduitjson")
     */
    public function modifierProduitJson(NormalizerInterface $normalizer, Request $request, UserRepository $usr, CategorieRepository $cat
        ,$idUser,$idCategorie, $idProduit){
        $em=$this->getDoctrine()->getManager();
        $produit = new Produit();
        $user = new User();
        $user = $usr->find($idUser);
        $categorie = new Categorie();
        $categorie = $cat->find($idCategorie);
        $produit=$em->getRepository(Produit::class)->find($idProduit);
        $produit->setNom($request->get('nom'));
        $produit->setReference($request->get('reference'));
        $produit->setPrix($request->get('prix'));
        $produit->setDescription($request->get('description'));
        $produit->setPromotion($request->get('promotion'));
        $produit->setImage($request->get('image'));
        $produit->setPrixFinal($request->get('prixFinal'));
        $produit->setIdUser($user);
        $produit->setIdCategorie($categorie);
        $em->flush();
        $json_content = $normalizer->normalize($produit, 'json',['groups'=>'productsgroup']);
        return new Response(json_encode($json_content));
    }


    // Recherche Ajax

    /**
     * @param Request $request
     * @param ProduitRepository $repo
     * @return Response
     * @Route ("/recherche/", name="ajax_search_product")
     */

    public function searchAction(Request $request, ProduitRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $produits =  $repo->findEntitiesByString($requestString);
        if(!$produits) {
            $result['produits']['error'] = " ⚠   Aucun produit n'a été trouvé! Veuillez saisir une autre chose! ";
        } else {
            $result['produits'] = $this->getRealEntities($produits);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($produits ){
        foreach ($produits as $produits){
            $realEntities[$produits->getIdProduit()] = [$produits->getNom(),$produits->getReference(),$produits->getPrix(),$produits->getDescription(),$produits->getIdUser()->getEmail(),$produits->getPromotion()
                ,$produits->getImage(),$produits->getPrixFinal(),$produits->getIdCategorie()->getNomCategorie()];
        }
        return $realEntities;
    }

    /**
     * @return void
     * @Route("/qrCode", name="qr_function")
     */
    public function genQrCode(ProduitRepository $repo){
////    code QR
//        $this->builder = $builder;
////        $url = 'https://www.google.com/search?q=';
//
//        $objDateTime = new \DateTime('NOW');
//        $dateString = $objDateTime->format('d-m-Y H:i:s');
//    $query='$id';
//        $path = dirname(__DIR__, 2).'/public/assets/';
//
//        // set qrcode
//        $result = $this->builder
//            ->data($query)
//            ->encoding(new Encoding('UTF-8'))
//            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
//            ->size(400)
//            ->margin(10)
//            ->labelText($dateString)
//            ->labelAlignment(new LabelAlignmentCenter())
//            ->labelMargin(new Margin(15, 5, 5, 5))
//            ->build()
//        ;
//
//        //generate name
//        $namePng = uniqid('', '') . '.png';
//
//        //Save img png
//        $result->saveToFile($path.'qr-code/'.$namePng);
//    $result->getDataUri();
        return $this->render('produit/qrtemp.html.twig');
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

            $nomProd[] = $produit->getNom();

//           $pr= $produit->getIdCategorie();
//           $pr1=count($pr);
            $prixProd[] = $produit->getPrixFinal();
        }
        return $this->render('produit/stats.html.twig',[
            'nomProd' =>json_encode($nomProd),
            'prixProd'=>json_encode($prixProd)
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route ("/generateExcel", name="excel")
     */

    public function generateExcel(ProduitRepository $prodrepo){
//        $produit = new Produit();
        $produits = $prodrepo->findAll();
        $spreadsheet = new Spreadsheet();
//        for
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Référence');
        $sheet->setCellValue('C1', 'Prix');
        $sheet->setCellValue('D1', 'Description');
        $sheet->setCellValue('E1', 'Fournisseur');
        $sheet->setCellValue('F1', 'Promotion');
//        $sheet->setCellValue('A1', 'Prix Final');
        $sheet->setCellValue('G1', 'Catégorie');

//        $sheet->setTitle("products");

        //for
        $sn=1;
        foreach ($produits as $p) {
//         dd($p->getNom());
            $sheet->setCellValue('A'.$sn,$p->getNom());
            $sheet->setCellValue('B'.$sn,$p->getReference());
            $sheet->setCellValue('C'.$sn,$p->getPrix());
            $sheet->setCellValue('D'.$sn,$p->getDescription());
            $sheet->setCellValue('E'.$sn,$p->getIdUser()->getEmail());
            $sheet->setCellValue('F'.$sn,$p->getPromotion());
            $sheet->setCellValue('G'.$sn,$p->getIdCategorie()->getNomCategorie());
            $sheet->setCellValue('H'.$sn,$p->getPrixFinal());
            $sn++;

        }


        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'produits.xlsx';
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

    /**
     * @return Response
     * @Route("/{idProduit}/barcode", name="barcode")
     */
    public function generateBarcode(ProduitRepository $repo, Produit $produit): Response
    {
        $generator = new BarcodeGeneratorHTML();
        echo $generator->getBarcode(($produit->getReference()), $generator::TYPE_CODE_39);
        return $this->render('produit/testbarcode.html.twig',[$generator]);
    }

//    public function countNumberProducts()
//    {
//       // requete SQL
//        $entityManager=$this->getEntityManger();
//        $query = $entityManager->createQuery('SELECT categorie.nom_categorie, COUNT(categorie.nom_categorie) as nbr_produits FROM categorie, produit WHERE (categorie.id_categorie = produit.id_categorie) GROUP BY categorie.nom_categorie');
//        $req=$query->getResult();
//    }

//mobile
//    /**
//     * @param Request $request
//     * @param NormalizerInterface $normalizer
//     * @return Response
//     * @throws ExceptionInterface
//     * @Route ("/addProductJSON/new",name="addProductJSON")
//     */
//    public function addProductJson(Request $request,NormalizerInterface $normalizer){
//        $em= $this->getDoctrine()->getManager();
//        $produit = new Produit();
//        $produit->setNom($request->get('nom'));
//        $produit->setPrix($request->get('prix'));
//        $produit->setReference($request->get('reference'));
//        $produit->setDescription($request->get('description'));
//        $produit->setPromotion($request->get('promotion'));
//        $produit->setImage($request->get('image'));
//        $produit->setIdUser($request->get('idUser'));
//        $produit->setIdCategorie($request->get('idCategorie'));
//        //  $produit->getIdCategorie()->setNom($request->get('nom'));
//        $em->persist($produit);
//        $em->flush();
//        $jsonContent = $normalizer->normalize($produit,'json',['groups'=>'post:read']);
//        return new Response(json_encode($jsonContent));
//
//    }
//Affichage mobile


}

