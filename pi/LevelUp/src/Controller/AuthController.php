<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): RedirectResponse
    {     
        return $this->redirectToRoute("app_login");
    
        
    }
    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     */
    public function oubliPass (Request $request, UserRepository $userRepo, FlashyNotifier $flashy, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator
    ): Response
    {

        $form = $this->createForm(ResetPassType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
           
            $donnees = $form->getData();

            
            $user = $userRepo->findOneByEmail($donnees['email']);

          
            if ($user === null) {
               
                $flashy->error('Cette adresse e-mail est inconnue!', 'http://your-awesome-link.com');

                return $this->redirectToRoute('app_login');
            }

            // On génère un token
            $token = $tokenGenerator->generateToken();

            // On essaie d'écrire le token en base de données
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('app_reset_password', array('token' => $token),
                UrlGeneratorInterface::ABSOLUTE_URL);

            // On génère l'e-mail
            $message = (new Email())
            ->from('lup634771@gmail.com')
            ->to($user->getEmail())
            ->subject('Reset Password')
            ->text('Sending emails is fun again!')
            ->html("Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site levelUp. Veuillez cliquer sur le lien suivant : " . $url,
            'text/html');


  
            $mailer->send($message);

       
            $flashy->success('E-mail de réinitialisation du mot de passe envoyé !', 'http://your-awesome-link.com');
            
            // On redirige vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // On envoie le formulaire à la vue
        return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
    }
    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, FlashyNotifier $flashy, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On cherche un utilisateur avec le token donné
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $flashy->error('Token Inconnu', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_login');
        }


        if ($request->isMethod('POST')) {
            // On supprime le token
            $user->setResetToken(null);

            // On chiffre le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // On crée le message flash
            $flashy->success('Mot de passe modifié', 'http://your-awesome-link.com');
          

            // On redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }
        else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
}
