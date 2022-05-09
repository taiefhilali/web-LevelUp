<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\FactureType;
use App\Repository\ProduitRepository;
use App\Repository\StockRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repository\FactureRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Mime\Email;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
{#use Knp\Component\Pager\PaginatorInterface;#}

    /**
     * @Route("/facture")
     */
    class FactureController extends AbstractController
    {
        /**
         * @Route("/backfacturemobile", name="appshowfacturemobile")
         */
        public function mobilefindfacture(FactureRepository  $factureRepository, Request $request, NormalizerInterface $Normalizer): Response
        {
            $repository = $this->getdoctrine()->getRepository(Facture::class);
            $facture = $repository->findAll();
            $jsonContent = $Normalizer->normalize($facture, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($jsonContent));
        }

        /**
         * @Route("/", name="app_facture_index", methods={"GET"})
         */
        public function index(Request $request ,FactureRepository $factureRepository,PaginatorInterface $paginator): Response
        {
            $donnees=$factureRepository->findAll();
            $donnees_rev=array_reverse($donnees);
            $facture= $paginator->paginate(
                $donnees_rev,
                $request->query->getInt('page',1),2

            );
            return $this->render('facture/index.html.twig',
                ['factures'=>$facture]
            );

        }

        /**
         * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
         * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
         * @Route ("/Excel", name="app_facture_excel")
         */

        public function Excel(FactureRepository $factureposity, UserRepository  $usr)
        {
            //$produit= new Produit();
            $factures = $factureposity->findAll();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Date');
            $sheet->setCellValue('B1', 'Prix total');

            $sheet->setCellValue('C1', 'User');

            $s = 2;
            foreach ($factures as $facture) {


                $sheet->setCellValue('A' .$s, $facture->getDate());
                $sheet->setCellValue('B' .$s, $facture->getPrixTotal());
                $sheet->setCellValue('C' .$s, $facture->getIdUser()->getEmail());
                $s++;

            }

            $writer = new Xlsx($spreadsheet);

            $fileName = 'facture.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);

            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

        /**
         * @Route("/imprimer", name="app_facture_imprimer")
         */
        public function imprimer(FactureRepository $factureRepository): Response

        {

            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');

            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            $facture = $factureRepository->findAll();

            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('facture/imprimer.html.twig', [
                'facture' => $facture,
            ]);

            // Load HTML to Dompdf
            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser (inline view)
            $dompdf->stream("Facture.pdf", [
                "Attachment" => true
            ]);

            return $this->render('facture/imprimer.html.twig', [
                'factures' => $factureRepository->findAll(),
            ]);
        }
        /**
         * @Route("/pdf", name="app_facture_pdf", methods={"GET"})
         */
        public function pdf(FactureRepository $factureRepository, UserRepository $userRepo, MailerInterface $mailer)
        {

            $pdfOptions = new Options();
            $dompdf = new Dompdf($pdfOptions);
            $facture = $factureRepository->findBy(['idFacture' => 30]);

            $usr = $userRepo->find(1);
            $html = $this->renderView('facture/pdf.html.twig', [
                'user' => $usr,
                'facture' => $facture
            ]);
            $dompdf->loadHtml($html);
            $dompdf->render();
            $output = $dompdf->output();
            $message = (new Email())
                ->from('lup634771@gmail.com')
                ->to('tabbenasiwar47@gmail.com')
                ->subject('Votre Facture !!')
                ->Text("Facture")
                ->attach($output, "Facture.pdf", 'application/pdf');

            $mailer->send($message);

            return $this->redirectToRoute('app_facture_index');



        }


        /**
         * @Route("/new", name="app_facture_new", methods={"GET", "POST"})
         */
        public function new(Request $request, FactureRepository $factureRepository): Response
        {
            $facture = new Facture();
            $form = $this->createForm(FactureType::class, $facture);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {


                $factureRepository->add($facture);
                return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('facture/new.html.twig', [
                'facture' => $facture,
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/{idFacture}", name="app_facture_show", methods={"GET"})
         */
        public function show(Facture $facture): Response
        {
            return $this->render('facture/show.html.twig', [
                'facture' => $facture,
            ]);
        }

        /**
         * @Route("/{idFacture}/edit", name="app_facture_edit", methods={"GET", "POST"})
         */
        public function edit(Request $request, Facture $facture, FactureRepository $factureRepository): Response
        {
            $form = $this->createForm(FactureType::class, $facture);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $factureRepository->add($facture);
                return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('facture/edit.html.twig', [
                'facture' => $facture,
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/{idFacture}", name="app_facture_delete", methods={"POST"})
         */
        public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
        {
            if ($this->isCsrfTokenValid('delete' . $facture->getIdFacture(), $request->request->get('_token'))) {
                $factureRepository->remove($facture);
            }

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }
        /**
         * @param NormalizerInterface $normalizer
         * @param Request $request
         * @return Response
         * @throws ExceptionInterface
         * @Route("/editFactureJson/edit/{idFacture}/{idUser}" , name="editFacturejson")
         */
        public function editFactureJson(NormalizerInterface $normalizer, Request $request, UserRepository $usr
            ,$idUser,$idFacture){
            $em=$this->getDoctrine()->getManager();
            $facture = new Facture();
            $facture=$em->getRepository(Facture::class)->find($idFacture);

            $user = new User();
            $user = $usr->find($idUser);
            $facture->setDate(new \DateTime());
            $facture->setPrixTotal($request->get('prix_total'));
            $facture->setIdUser($user);
            //$facture=$em->getRepository(Facture::class)->find($idFacture);

            $em->flush();
            $json_content = $normalizer->normalize($facture, 'json',['groups'=>'facture']);
            return new Response(json_encode($json_content));
        }

        /**
         * @param NormalizerInterface $normalizer
         * @param Request $request
         * @return Response
         * @throws ExceptionInterface
         * @Route("/addFactureJson/add/{idUser}" , name="addFacturejson")
         */
        public function addDactureJson(NormalizerInterface $normalizer, Request $request, UserRepository $usr,
            $idUser){
            $em=$this->getDoctrine()->getManager();
            $facture = new Facture();
            $user = new User();
            $user = $usr->find($idUser);

            $facture->setDate(new \DateTime());
            $facture->setPrixTotal($request->get('prix_total'));

            $facture->setIdUser($user);

            $em->persist(  $facture);
            $em->flush();
            $json_content = $normalizer->normalize(  $facture, 'json',['groups'=>'facture']);
            return new Response(json_encode($json_content));
        }


            /**
             * @Route("/deleteFactureJSON/{idFacture}",name="deletefactureJSON")
             *
             *
             */
            public function deleteFactureJSON(Request $request, NormalizerInterface $Normalizer, $idFacture)
            {
                $em = $this->getDoctrine()->getManager();
                $facture = $em->getRepository(Facture::class)->find($idFacture);
                $em->remove($facture);
                $em->flush();
                $jsonContent = $Normalizer->normalize($facture, 'json', ['groups' => 'post:read']);
                return new Response("Facture deleted successfully" . json_encode($jsonContent));;


            }

        }

}