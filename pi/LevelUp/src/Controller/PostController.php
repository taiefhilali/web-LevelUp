<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Vote;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{


    /**
     * @Route("/backpostmobile", name="appshowmobile")
     */
    public function mobilefind(PostRepository $postRepository, Request $request, NormalizerInterface $Normalizer): Response
    {
        $repository = $this->getdoctrine()->getRepository(Post::class);
        $posts = $repository->findAll();
        $jsonContent = $Normalizer->normalize($posts, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }



    /**
     * @Route("/deletePosttJSON{id}",name="deletePostJSON")
     *
     *
     */
    public function deletePostJSON(Request $request,NormalizerInterface $Normalizer,$id){
        $em=$this->getDoctrine()->getManager();
        $post=$em->getRepository(Post::class)->find($id);
        $em->remove($post);
        $em->flush();
        $jsonContent=$Normalizer->normalize($post, 'json',['groups'=>'post:read']);
        return new Response("Post deleted successfully".json_encode($jsonContent));;


    }

    /**
     * @Route("/updatePostJSON{id}",name="updatePostJSON")
     *
     *
     */
    public function updatePostJSON(Request $request, NormalizerInterface $Normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);
        $post->setContent($request->get('content'));
        $post->setTitle($request->get('title'));
        $em->flush();
        $jsonContent = $Normalizer->normalize($post, 'json', ['groups' => 'post:read']);
        return new Response("Information updated successfully" . json_encode($jsonContent));;


    }



    /**
     * @Route("/statistique", name="app_post_statistique")
     *
     * public function statpost(){
     *
     * $repository = $this->getDoctrine()->getRepository(Post::class);
     * $Post = $repository->findAll();
     *
     * $em = $this->getDoctrine()->getManager();
     *
     *
     * $pr1=0;
     * $pr2=0;
     *
     *
     *
     * foreach ($Post as $Post)
     * {
     * if ( $Post->getresp()=="5")  :
     *
     * $pr1+=1;
     * else:
     *
     * $pr2+=1;
     *
     *
     * endif;
     *
     * }
     *
     * $pieChart = new PieChart();
     * $pieChart->getData()->setArrayToDataTable(
     * [['resp', 'label'],
     * ['5', $pr1],
     * ['>5', $pr2],
     * ]
     * );
     * $pieChart->getOptions()->setTitle('STATISTIQUE DU MEILLEUR POST SELON RATE');
     * $pieChart->getOptions()->setHeight(500);
     * $pieChart->getOptions()->setWidth(900);
     * $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
     * $pieChart->getOptions()->getTitleTextStyle()->setColor('#91b59f');
     * $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
     * $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
     * $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
     *
     * return $this->render('post/stats.html.twig', array('piechart' => $pieChart));
     * }
     */

    /**
     * @Route("/backpost", name="app_post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository, Request $request, PaginatorInterface $paginator, CommentRepository $commentrepo): Response
    {
        $repository = $this->getdoctrine()->getRepository(Post::class);
        $posts = $repository->findAll();
        $posts = $paginator->paginate($posts, $request->query->getInt('page', 1), 2);
        return $this->render('post/index.html.twig', ['posts' => $posts]);
    }


    /*
        /**
         * @Route("/search", name="app_post_index", methods={"GET"})
         */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts = $em->getRepository(Post::class)->findBytitle($requestString);
        if (!$posts) {
            $result['posts']['error'] = "Post Not found :( ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($posts)
    {
        foreach ($posts as $posts) {
            $realEntities[$posts->getId()] = [$posts->gettitle()];

        }
        return $realEntities;
    }

    /**
     * @Route("/paginatorpost", name="app_post_indexpaginator", methods={"GET"})
     */
    public function indexpaginator(Request $request, PaginatorInterface $paginator): Response
    {
        $repository = $this->getdoctrine()->getRepository(Post::class);
        $posts = $repository->findAll();
        $posts = $paginator->paginate($posts, $request->query->getInt('page', 1), 3);
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/delete{id}", name="app_post_delete", methods={"POST","GET"})
     */
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);
        }

        return $this->redirectToRoute('app_post_indexFront', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{id}deletebackpost", name="app_post_deleteback", methods={"POST"})
     */
    public function deleteback(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);
        }

        return $this->redirectToRoute('app_post_indexpaginator', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/indexFront", name="app_post_indexFront", methods={"GET"})
     */
    public function indexFront(PostRepository $postRepository): Response
    {
        return $this->render('post/indexFront.html.twig', [
            'posts' => $postRepository->findBy([], ['id' => 'desc']),
        ]);

    }


    /**
     * @Route("/new", name="app_post_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PostRepository $postRepository, UserRepository  $userRepository): Response
    {  $user=$userRepository->find($request->getSession()->get('id'));
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setIdUser($user);
            $postRepository->add($post);

            return $this->redirectToRoute('app_post_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);

    }


    /**
     * @Route("/like", name="like")
     */
    public function likepost(Request $request, PostRepository $postRepo, VoteRepository $voteRepository, UserRepository $userRepository)
    {

        $repository = $this->getdoctrine()->getRepository(Post::class);
        $vtrepo = $this->getdoctrine()->getRepository(Vote::class);
        $post = $postRepo->find($request->query->get('postid'));
        $user = $userRepository->find($request->query->get('userid'));
        $post->setIdUser($user);


        $vote = new Vote();

        $vote->setId($request->query->get($post->getId()));
        $vote->setIdUser($user->getIdUser());
        $vote->setVoteType(1);
        $voteRepository->add($vote);


        return new Response('Saved new product with id ' . $vote->getId());
    }


    /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(PostRepository $postRepo, CommentRepository $commentRepo)
    {
        // On va chercher toutes les catégories
        $posts = $postRepo->findAll();

        $categNom = [];
        $categColor = [];
        $categCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($posts as $post) {
            $categNom[] = $post->getTitle();
            $categColor[] = $post->getDatep();
            $categCount[] = count(array($post->getTitle()));
        }

        // On va chercher le nombre d'annonces publiées par date
        $comments = $commentRepo->countByDate();

        $dates = [];
        $annoncesCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($comments as $comment) {
            $dates[] = $comment['Resp'];
            $annoncesCount[] = $comment['count'];
        }

        return $this->render('post/stats.html.twig', [
            'title' => json_encode($categNom),
            'datep' => json_encode($categColor),
            'categCount' => json_encode($categCount),
            'Resp' => json_encode($dates),
            'annoncesCount' => json_encode($annoncesCount),
        ]);
    }


    /**
     * @Route("/{id}", name="app_post_show", methods={"POST","GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="app_post_e", methods={"POST","GET"})
     */
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post);
            return $this->redirectToRoute('app_post_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}showbackpost", name="app_post_showback", methods={"GET"})
     */
    public function showback(Post $post): Response
    {
        return $this->render('post/showback.html.twig', [
            'post' => $post,
        ]);
    }





// codename one


    /**
     * @Route("/addPostJSON/new",name="addPostJSON")
     *
     *
     */
    public function addPostJSON(Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $post = new Post();
        $post->setContent($request->get('content'));
        $post->setTitle($request->get('title'));

        $em->persist($post);
        $em->flush();
        $jsonContent = $Normalizer->normalize($post, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));;

    }




}