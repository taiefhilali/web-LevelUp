<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Vote;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/post")
 */
class PostController extends AbstractController
{


    /**
     * @Route("/backpost", name="app_post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository,Request $request, PaginatorInterface $paginator,CommentRepository $commentrepo ): Response
    {
        $repository = $this->getdoctrine()->getRepository(Post::class);
        $posts= $repository->findAll();
        $posts = $paginator->paginate($posts, $request->query->getInt('page',1), 2);
        return $this->render('post/index.html.twig',['posts' => $posts]);
    }



/*
    /**
     * @Route("/search", name="app_post_index", methods={"GET"})
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository(Post::class)->findBytitle($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Post Not found :( ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
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
        $posts= $repository->findAll();
        $posts = $paginator->paginate($posts, $request->query->getInt('page',1), 3);
        return $this->render('post/index.html.twig',[
            'posts' => $posts,
        ]);
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
    public function new(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function likepost(Request $request,PostRepository $postRepo, VoteRepository  $voteRepository,UserRepository $userRepository)
    {

        $repository = $this->getdoctrine()->getRepository(Post::class);
        $vtrepo = $this->getdoctrine()->getRepository(Vote::class);
        $post= $postRepo->find($request->query->get('postid'));
        $user = $userRepository->find($request->query->get('userid'));
        $post->setIdUser($user);



        $vote = new Vote();

        $vote->setId($request->query->get($post->getId()));
        $vote->setIdUser($user->getIdUser());
        $vote->setVoteType(1);
        $voteRepository->add($vote);





        return new Response('Saved new product with id '.$vote->getId());}



    /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(PostRepository $postRepo, CommentRepository $commentRepo){
        // On va chercher toutes les catégories
        $posts = $postRepo->findAll();

        $categNom = [];
        $categColor = [];
        $categCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($posts as $post){
            $categNom[] = $post->getTitle();
            $categColor[] = $post->getDatep();
            $categCount[] = count(array($post->getTitle()));
        }

        // On va chercher le nombre d'annonces publiées par date
        $comments = $commentRepo->countByDate();

        $dates = [];
        $annoncesCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($comments as $comment){
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
     * @Route("/{id}", name="app_post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
    /**
     * @Route("/{id}", name="app_post_delete", methods={"POST"})
     */
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);
        }

        return $this->redirectToRoute('app_post_indexFront', [], Response::HTTP_SEE_OTHER);
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

    /**
     * @Route("/{id}/edit", name="app_post_edit", methods={"GET", "POST"})
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
     * @Route("/{id}deletebackpost", name="app_post_deleteback", methods={"POST"})
     */
    public function deleteback(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }




}