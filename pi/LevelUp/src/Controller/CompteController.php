<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CompteType;
use App\Form\PassType;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    
    /**
     * @Route("/account", name="app_account", methods={"GET","POST"})
     */
    public function indexAccount(Request $request,FlashyNotifier $flashy, UserRepository $userRepository ): Response
    {   $formpass = $this->createForm(PassType::class);
        $formpass->handleRequest($request);
        $user= new User();
        $user=$userRepository->find($request->getSession()->get('id'));
        $form = $this->createForm(CompteType::class, $user);
        $form->handleRequest($request);

        
        if ($form->isSubmitted()) {
            $userRepository->add($user);
            $request->getSession()->set('id', $user->getIdUser());
            $request->getSession()->set('email', $request->request->get('email'));
            $request->getSession()->set('nom', $user->getNom());
            $request->getSession()->set('prenom', $user->getPrenom());
            $request->getSession()->set('role', $user->getRole());
            $request->getSession()->set('password', $user->getPassword());
            $request->getSession()->set('image', $user->getImage());
            $flashy->success('Votre compte a ete bien modifié ', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }
        


       
        return $this->render('account.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'formpass' => $formpass->createView()]);
   }
     /**
     * @Route("/accountClient", name="app_accountClient", methods={"GET","POST"})
     */
    public function indexAccountClient(Request $request,FlashyNotifier $flashy, UserRepository $userRepository ): Response
    {   $formpass = $this->createForm(PassType::class);
        $formpass->handleRequest($request);
        $user= new User();
        $user=$userRepository->find($request->getSession()->get('id'));
        $form = $this->createForm(CompteType::class, $user);
        $form->handleRequest($request);

        
        if ($form->isSubmitted()) {
            $userRepository->add($user);
            $request->getSession()->set('id', $user->getIdUser());
            $request->getSession()->set('email', $request->request->get('email'));
            $request->getSession()->set('nom', $user->getNom());
            $request->getSession()->set('prenom', $user->getPrenom());
            $request->getSession()->set('role', $user->getRole());
            $request->getSession()->set('password', $user->getPassword());
            $request->getSession()->set('image', $user->getImage());
            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->render('accountClient.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'formpass' => $formpass->createView()]);
   }
}
