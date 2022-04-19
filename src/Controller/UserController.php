<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\Mailer;
use App\Entity\User;
use App\Entity\Administrateur;
use App\Entity\Livreur;
use App\Entity\Fournisseur;
use App\Form\EditUserType;
use App\Form\PassType;
use App\Form\InscriptionType;
use App\Security\LoginFormAuthenticator;
use App\Form\UserType;
use App\Form\CompteType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ReCaptcha\ReCaptcha; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;



/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_user_index", methods={"GET","POST"})
     */
    public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $collection=$userRepository->findAll();
        $reversed_array = array_reverse($collection);       
        $users = $paginator->paginate(
            $reversed_array , // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
       if ($request->isMethod("POST"))
       {
           $prenom=$request->get('prenom');
           $users = $paginator->paginate(
            $userRepository->findBy(["prenom"=>$prenom]) , // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
         
       }
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
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
            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }
        

        
    
        
       
        return $this->render('account.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'formpass' => $formpass->createView()]);
   }
    /**
     * @Route("/changePassword", name="changePassword", methods={"GET","POST"})
     */
    public function changePassword(Request $request,FlashyNotifier $flashy, UserRepository $userRepository ): Response
    {   $user=new User();
        $formpass = $this->createForm(PassType::class,$user);
        $formpass->handleRequest($request);
        
       if ($formpass->isSubmitted()) {
            
           $userPass=$userRepository->find($request->getSession()->get('id'));
            if(strcasecmp($user->getRepeatPassword(), $userPass->getPassword() == 0))
            {
                $request->getSession()->set('password',$user->getPassword());
                $user=$userRepository->find($request->getSession()->get('id'));
                $user->setPassword(  $request->getSession()->get('password'));
                $userRepository->add($user);
                $flashy->success('Mot de passe modifié!', 'http://your-awesome-link.com');
                    return $this->redirectToRoute('changePassword', [], Response::HTTP_SEE_OTHER);
                    
            }
            else
            {
                $flashy->error('Ancien mot de passe est incorrect', 'http://your-awesome-link.com');
                return $this->redirectToRoute('changePassword', [], Response::HTTP_SEE_OTHER);
              
            }
        }
       
        return $this->render('changePassword.html.twig', [
        
            'formpass' => $formpass->createView()]);
   }

    /**
     * @Route("/inscription", name="app_user_inscription", methods={"GET", "POST"})
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
              $sexe = $user->getSexe();
              $userRepository->add($user);
              $client->setIdUser($user);
              $client->setSexe($sexe);
              $em = $this->getDoctrine()->getManager();
              $em->persist($client);
              $em->flush();
              $user->setImageFile(null);
              $mailer->sendEmail($user->getEmail(),$user->getActivationToken()); 
              $this->addFlash('message','Le mesage a bien été envoyé ');
                $flashy->success('Inscription validée, veuillez activer votre compte via le lien envoyé a votre adresse e-mail ', 'http://your-awesome-link.com');
                return$this->redirectToRoute('app_user_inscription');
            }

           
        }

        return $this->render('user/inscription.html.twig', [

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
            $user->setActivationToken(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //we send message flash
            $this->addFlash('message','vous avez bien active votre compte');
            return$this->redirectToRoute('app_login');
        }

    /**
     * @Route("/new", name="app_user_new")
     */
    function new (Request $request, UserRepository $userRepository,UserPasswordEncoderInterface $encoder): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            
             $user->setPassword(
                $encoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
              );
           
            $user->setAdresse(" ");
            $user->setTel(" ");
            $user->setSexe("homme/femme");
            $user->setImage("avatar-user.png");

            $userRepository->add($user);
            
            if($user->getRole()=="administrateur"){
                
                $admin= new Administrateur();
                $admin->setIdUser($user);
                $admin->setCin("00000000");
                $em=$this->getDoctrine()->getManager();
                $em->persist($admin);
                $em->flush();           
            }
            else if($user->getRole()=="livreur"){
                $livreur = new Livreur();
                $livreur->setIdUser($user);
                $livreur->setCin("00000000");
                $livreur->setVehicule("0000");
                $em = $this->getDoctrine()->getManager();
                $em->persist($livreur);
                $em->flush();        
            }
            else if($user->getRole()=="fournisseur"){
                $fourni = new Fournisseur();
                $fourni->setIdUser($user);
                $fourni->setCin("00000000");
                $fourni->setnomMarque(" ");
                $em = $this->getDoctrine()->getManager();
                $em->persist($fourni);
                $em->flush();        
            }

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{idUser}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $userRepository->add($user);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getIdUser(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
