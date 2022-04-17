<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{





    /**
     * @param CommentRepository $Repository
     * @return Response
     * @Route ("/statss", name="stat")
     */

    public function statistiques(CommentRepository $commentRepository){
        $comment = $commentRepository->countByResp();
        $resp = [];
        $commentsCount = [];
        foreach($comment as $comments){

            $resp [] = $comments['resp'];
            $commentsCount[] = $comments['count'];
        }
        return $this->render('comment/stats.html.twig', [
            'resp' => $resp,
            'commentsCount' => $commentsCount
        ]);
    }
    /**
     * @Route("/statistique", name="statistique")
     */
    public function stat(){

        $repository = $this->getDoctrine()->getRepository(Comment::class);
        $Comment = $repository->findAll();

        $em = $this->getDoctrine()->getManager();


        $pr1=0;
        $pr2=0;



        foreach ($Comment as $Comment)
        {
            if ( $Comment->getresp()=="5")  :

                $pr1+=1;
            else:

                $pr2+=1;


            endif;

        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['resp', 'label'],
                ['5', $pr1],
                ['50', $pr2],
            ]
        );
        $pieChart->getOptions()->setTitle('STATISTIQUE DU MEILLEUR POST SELON RATE');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#91b59f');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('comment/stats.html.twig', array('piechart' => $pieChart));
    }



    /**
     * @Route("/b", name="app_comment_index_back", methods={"GET"})
     */
    public function indexback(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/back.html.twig', [
            'comments' => $commentRepository->findBy([],['idc'=>'desc']),
        ]);
    }


    /**
     * @Route("/new", name="app_comment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->add($comment);
            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }
/*
    /**
     * @Route("/", name="app_comment_index", methods={"GET"})

    public function index(Post $post ,Request $request ,CommentRepository $commentRepository): Response
    {

        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findBy([],['idc'=>'desc']),
        ]);
    }
 */
    /**
     * @Route("/{id}test", name="app_comment_index", methods={"GET"})
     */
    public function showback(Post $post,CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findBy(['idPost'=>$post->getId()],['idc'=>'desc']),
        ]);
    }

    /**
     * @Route("/{idc}", name="app_comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }


    /**
     * @Route("/{idc}/edit", name="app_comment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->add($comment);
            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{idc}", name="app_comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getIdc(), $request->request->get('_token'))) {
            $commentRepository->remove($comment);
        }

        return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    }


}
