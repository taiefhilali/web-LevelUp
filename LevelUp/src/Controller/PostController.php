<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
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
     * @Route("/paginatorpost", name="post_index", methods={"GET"})
     */
    public function indexpaginator(Request $request, PaginatorInterface $paginator): Response
    {
        $repository = $this->getdoctrine()->getRepository(Post::class);
        $posts= $repository->findAll();
        $posts = $paginator->paginate(
            $posts,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }


    /**
     * @Route("/backpost", name="app_post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy([], ['id' => 'desc']),
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
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }


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
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
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

}