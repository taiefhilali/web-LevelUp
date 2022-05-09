<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vote")
 */
class VoteController extends AbstractController
{
    /**
     * @Route("/", name="app_vote_index", methods={"GET"})
     */
    public function index(VoteRepository $voteRepository): Response
    {
        return $this->render('vote/index.html.twig', [
            'votes' => $voteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_vote_new", methods={"GET", "POST"})
     */
    public function new(Request $request, VoteRepository $voteRepository): Response
    {
        $vote = new Vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voteRepository->add($vote);
            return $this->redirectToRoute('app_vote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vote/new.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idv}", name="app_vote_show", methods={"GET"})
     */
    public function show(Vote $vote): Response
    {
        return $this->render('vote/show.html.twig', [
            'vote' => $vote,
        ]);
    }

    /**
     * @Route("/{idv}/edit", name="app_vote_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Vote $vote, VoteRepository $voteRepository): Response
    {
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voteRepository->add($vote);
            return $this->redirectToRoute('app_vote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vote/edit.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idv}", name="app_vote_delete", methods={"POST"})
     */
    public function delete(Request $request, Vote $vote, VoteRepository $voteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vote->getIdv(), $request->request->get('_token'))) {
            $voteRepository->remove($vote);
        }

        return $this->redirectToRoute('app_vote_index', [], Response::HTTP_SEE_OTHER);
    }



    /**
     *
     *
     *@Route ("/{id}/like_dislike", name="vote")
     */

    public function like_dislike( $id ,VoteRepository $voteRepository,UserRepository $userRepository,PostRepository $postRepository,EntityManagerInterface $em){
    $user_id=1;
    $vote=$voteRepository->findOneBy(['idUser'=> $user_id,'id'=>$id]);
    if($vote){
        $voteRepository->remove($vote);




    }else{
       $v= new Vote();
       $v->setIdUser($userRepository->find($user_id));
        $v->setId($postRepository->find($id));
        $v->setVoteType(0);
        $em->persist($v);

    }

        $em->flush();
        return $this->redirectToRoute('app_post_indexFront', [], Response::HTTP_SEE_OTHER);


    }


    /**
     *
     *
     *@Route ("/{id}/check")
     */
    public function check($id,VoteRepository $voteRepository){
        $user_id=1;
        $vote=$voteRepository->findOneBy(['idUser'=> $user_id,'id'=>$id]);
        if($vote){
            $res=1;

        }else {

            $res=0;
        }

        return $this->json(['data'=>$res,'count'=>count($voteRepository->findBy(['id'=>$id]))]);

    }


}
