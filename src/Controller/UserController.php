<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Administrateur;
use App\Entity\Livreur;
use App\Entity\Fournisseur;
use App\Form\EditUserType;
use App\Form\PassType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @Route("/changePassword", name="changePassword", methods={"GET","POST"})
     */
    public function changePassword( ): Response
    {   
        
        return $this->render('user/dashboard.html.twig', [
        
            'formpass' => 3]);
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
            $user->setActivationToken("NULL");
          
            $user->setImage("avatar-user.png");

            
            
            if($user->getRole()=="administrateur"){
                $user->setRoles(['ROLE_ADMIN']);
                $userRepository->add($user);

                $admin= new Administrateur();
                $admin->setIdUser($user);
                $admin->setCin("00000000");
                $em=$this->getDoctrine()->getManager();
                $em->persist($admin);
                $em->flush();           
            }
            else if($user->getRole()=="livreur"){
                $user->setRoles(['ROLE_LIVREUR']);
                $userRepository->add($user);
                $livreur = new Livreur();
                $livreur->setIdUser($user);
                $livreur->setCin("00000000");
                $livreur->setVehicule("0000");
                $em = $this->getDoctrine()->getManager();
                $em->persist($livreur);
                $em->flush();        
            }
            else if($user->getRole()=="fournisseur"){
                $user->setRoles(['ROLE_FOURNISSEUR']);
                $userRepository->add($user);
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
            if($user->getRole()=="administrateur"){
                $user->setRoles(['ROLE_ADMIN']);
                $userRepository->add($user);  
            }
            else if($user->getRole()=="livreur"){
                $user->setRoles(['ROLE_LIVREUR']);
               
                $userRepository->add($user);      
            }
            else if($user->getRole()=="fournisseur"){
                $user->setRoles(['ROLE_FOURNISSEUR']);
                $userRepository->add($user);      
                     
            }
            else if($user->getRole()=="client"){
                $user->setRoles(['ROLE_CLIENT']);
                $userRepository->add($user);      
            }


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
