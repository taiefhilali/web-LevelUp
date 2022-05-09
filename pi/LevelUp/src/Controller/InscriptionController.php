<?php

namespace App\Controller;
use App\Entity\Client;
use App\Service\Mailer;
use App\Entity\User;
use App\Form\InscriptionType;
use App\Security\LoginFormAuthenticator;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_inscription", methods={"GET", "POST"})
     */
    public function inscription(Request $request, UserRepository $userRepository, FlashyNotifier $flashy, GuardAuthenticatorHandler $guardHandler, UserPasswordEncoderInterface $encoder,LoginFormAuthenticator $authenticator, Mailer $mailer): Response
    {
        $user = new User();
        $client = new Client();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
        if (($form->isSubmitted() && $form->isValid())) {
            $recaptcha = new ReCaptcha('6Lf-fLYeAAAAALntWUcfkc5ZOikC5IzZtrbtZLEA');
            $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
          
            if (!$resp->isSuccess()) {
              $flashy->error('Vous etes un robot, inscrption non validée!', 'http://your-awesome-link.com');
            }
            else{
              
              $user->setPassword(
                $encoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
              );
              $user->setActivationToken(md5(uniqid()));
              $user->setRole("client");
              $user->setRoles(['ROLE_CLIENT']);
              $sexe = $user->getSexe();
              $userRepository->add($user);
              $client->setIdUser($user);
              $client->setSexe($sexe);
              $em = $this->getDoctrine()->getManager();
              $em->persist($client);
              $em->flush();
              $user->setImageFile(null);
              $mailer->sendEmail($user->getEmail(),$user->getActivationToken()); 
             
                $flashy->success('Inscription validée, veuillez activer votre compte via le lien envoyé a votre adresse e-mail ', 'http://your-awesome-link.com');
                return$this->redirectToRoute('app_inscription');
            }

           
        }

        return $this->render('inscription.html.twig', [

            'user' => $user,
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route ("/activation/{token}",name="activation")
     */
    public function activation($token, UserRepository $UserRepo) {
            $user= $UserRepo->findBy(['activation_token'=>$token]) ;
            //on verfie si un ultilisateur a ce token
            $user = $UserRepo->findOneBy(['activation_token'=> $token]);
            //si aucun utilisateur n'existe avec ce token
            if(!$user){
                //erreur 404
                throw $this->createNotFoundException('cet utilisateur n\'existe pas');
            }
            //on supprime le token
            $user->setActivationToken("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //we send message flash
            $this->addFlash('message','vous avez bien active votre compte');
            return$this->redirectToRoute('app_login');
        }





}
