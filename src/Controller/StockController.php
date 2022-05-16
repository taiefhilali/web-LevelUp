<?php

namespace App\Controller;
//require_once('vendor/autoload.php');
use App\Entity\Produit;
use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\ProduitRepository;
use App\Repository\StockRepository;
use MercurySeries\Flashy\FlashyNotifier;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Knp\Component\Pager\PaginatorInterface;
//use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/stock")
 */
{#require __DIR__ . '/vendor/autoload.php';#}

    class StockController extends AbstractController
    {
        /**
         * @Route("/backstockmobile", name="appshowstockmobile")
         */
        public function mobilefindstock(StockRepository $stockRepository, Request $request, NormalizerInterface $Normalizer): Response
        {
            $repository = $this->getdoctrine()->getRepository(Stock::class);
            $stock = $repository->findAll();
            $jsonContent = $Normalizer->normalize($stock, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($jsonContent));
        }

        /**
         * @Route("/", name="app_stock_index", methods={"GET"})
         */
        public function index(Request $request ,StockRepository $stockRepository ,PaginatorInterface $paginator): Response
        {
            $donnees=$stockRepository->findAll();
            $donnees_rev=array_reverse($donnees);
            $stock= $paginator->paginate(
                $donnees_rev,
                $request->query->getInt('page',1),5

            );
            return $this->render('stock/index.html.twig',
                ['stocks'=>$stock]
            );
        }
        /**
         * @Route("/message", name="app_stock_message", methods={ "GET" ,"POST"})
         */
        public function createFormessage(Request $request, StockRepository $stockRepository): Response
        {


            $sid = "AC177b85f030a6d314a32a66a14051e211";
            $token = "AC177b85f030a6d314a32a66a14051e211";
            $twilio = new Client($sid, $token);

            if (isset($_POST['submit'])) {
                $message = $twilio->messages
                           ->create("+21623202809", // to
                        array(
                            "body" => $_POST['message'],
                            "from" => "+19402918303"
                        )
                    );
                return $this->render('stock/message.html.twig');

                //print($message->sid);
                // header("Location: index.php?sent");
            }
            return $this->render('stock/message.html.twig'
            );        }




        /**
         * @Route("/mapAction", name="app_stock_map")
         */
        public function mapAction()
        {
            return $this->render('stock/map.html.twig');
        }


        /**
         * @Route("/new", name="app_stock_new", methods={"GET", "POST"})
         */
        public function new(Request $request, StockRepository $stockRepository): Response
        {
            $stock = new Stock();
            $form = $this->createForm(StockType::class, $stock);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $stockRepository->add($stock);
                $this->addFlash('info', 'added successfully');

                return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);

            }

            return $this->render('stock/new.html.twig', [
                'stock' => $stock,
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/{id}", name="app_stock_show", methods={"GET"})
         */
        public function show(Stock $stock): Response
        {
            return $this->render('stock/show.html.twig', [
                'stock' => $stock,
            ]);
        }

        /**
         * @Route("/{id}/edit", name="app_stock_edit", methods={"GET", "POST"})
         */
        public function edit(Request $request, Stock $stock, StockRepository $stockRepository): Response
        {
            $form = $this->createForm(StockType::class, $stock);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $stockRepository->add($stock);
                return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('stock/edit.html.twig', [
                'stock' => $stock,
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/{id}", name="app_stock_delete", methods={"POST"})
         */
        public function delete(Request $request, Stock $stock, StockRepository $stockRepository): Response
        {
            if ($this->isCsrfTokenValid('delete' . $stock->getId(), $request->request->get('_token'))) {
                $stockRepository->remove($stock);
            }

            return $this->redirectToRoute('app_stock_index', [], Response::HTTP_SEE_OTHER);
        }



        //codenameone

        /**
         * @Route("/addStockkJSON/new",name="addStockJSON")
         *
         *
         */
        public function addStockkJSON(Request $request, NormalizerInterface $Normalizer)
        {
            $em = $this->getDoctrine()->getManager();
            $stock = new Stock();
            $stock->setNom($request->get('nom'));
            $stock->setQuantite($request->get('quantite'));
            $stock->setEtat($request->get('etat'));



            $em->persist($stock);
            $em->flush();
            $jsonContent = $Normalizer->normalize($stock, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($jsonContent));;

        }

        /**
         * @Route("/updateStockJSON/{id}",name="updateStockJSON")
         *
         *
         */
        public function updateStockJSON(Request $request, NormalizerInterface $Normalizer, $id)
        {
            $em = $this->getDoctrine()->getManager();
            $stock= $em->getRepository(Stock::class)->find($id);
            $stock->setNom($request->get('nom'));
            $stock->setQuantite($request->get('quantite'));
            $stock->setEtat($request->get('etat'));
            $em->flush();
            $jsonContent = $Normalizer->normalize($stock, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($jsonContent));;

        }

        /**
         * @Route("/deleteStockJSON/{id}",name="deletestockJSON")
         *
         *
         */
        public function deleteStockJSON(Request $request, NormalizerInterface $Normalizer, $id)
        {
            $em = $this->getDoctrine()->getManager();
            $stock = $em->getRepository(Stock::class)->find($id);
            $em->remove($stock);
            $em->flush();
            $jsonContent = $Normalizer->normalize($stock, 'json', ['groups' => 'post:read']);
            return new Response("Stock deleted successfully" . json_encode($jsonContent));;


        }
    }
}
