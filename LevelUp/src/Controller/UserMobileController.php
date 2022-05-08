<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Entity\Client;
use App\Entity\Fournisseur;
use App\Entity\Livreur;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/mobile")
 */
class UserMobileController extends AbstractController
{

    /**
     * @Route("/index", name="app_user_indexMobile", methods={"GET"})
     */
    public function indexMobile(UserRepository $userRepository, NormalizerInterface $normalizer, Request $request, PaginatorInterface $paginator): Response
    {
        $collection = $userRepository->findAll();
        $users = array_reverse($collection);

        $jsonContent = $normalizer->normalize($users, 'json', ['groups' => 'productsgroup']);

        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/password/{email}/{code}", name="pass")
     */
    public function Passowrd( Mailer $mailer, $email, $code): Response
    {
        $mailer->send($email,$code);    
            return new Response("Email envoyé");
    }
    /**
     * @Route ("/add/{email}/{password}/{nom}/{prenom}/{role}", name="add")
     */
    public function add(Request $request, UserRepository $userRepository, SerializerInterface $serializerInterface, EntityManagerInterface $em, $email, $password, $nom, $prenom, $role): Response
    {
        $user = new User();

        $user->setEmail($email);
        $user->setPassword($password);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setRole($role);
        $user->setImage("avatar-user.jpg");
        if ($role == "administrateur") {
            $user->setRoles(['ROLE_ADMIN']);
            $userRepository->add($user);

            $admin = new Administrateur();
            $admin->setIdUser($user);
            $admin->setCin("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();
        } else if ($role == "livreur") {
            $user->setRoles(['ROLE_LIVREUR']);
            $userRepository->add($user);
            $livreur = new Livreur();
            $livreur->setIdUser($user);
            $livreur->setCin("NULL");
            $livreur->setVehicule("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();
        } else if ($role == "fournisseur") {
            $user->setRoles(['ROLE_FOURNISSEUR']);
            $userRepository->add($user);
            $fourni = new Fournisseur();
            $fourni->setIdUser($user);
            $fourni->setCin("NULL");
            $fourni->setnomMarque("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($fourni);
            $em->flush();
        } else if ($role == "client") {
            $user->setRoles(['ROLE_CLIENT']);

            $userRepository->add($user);
            $client = new Client();
            $client->setIdUser($user);
            $client->setSexe("NULL");

            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
        }

        return new Response('User ajouté avec succés JSON!');
    }
    /**
     * @Route ("/addUser/{email}/{password}/{nom}/{prenom}/{role}", name="addUser")
     */
    public function addUser(UserPasswordEncoderInterface $encoder,Request $request, UserRepository $userRepository, SerializerInterface $serializerInterface, EntityManagerInterface $em, $email, $password, $nom, $prenom, $role): Response
    {
        $user = new User();

        $user->setEmail($email);
        $user->setPassword(
            $encoder->encodePassword(
                $user,
                $password
            )
          );
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setRole($role);
        $user->setImage("avatar-user.jpg");
        $user->setTel("00000000");
        $user->setAdresse("");
        $date = new \DateTime('@' . strtotime('now'));
        $user->setDns($date);
        if ($role == "administrateur") {
            $user->setRoles(['ROLE_ADMIN']);
            $userRepository->add($user);

            $admin = new Administrateur();
            $admin->setIdUser($user);
            $admin->setCin("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();
        } else if ($role == "livreur") {
            $user->setRoles(['ROLE_LIVREUR']);
            $userRepository->add($user);
            $livreur = new Livreur();
            $livreur->setIdUser($user);
            $livreur->setCin("NULL");
            $livreur->setVehicule("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();
        } else if ($role == "fournisseur") {
            $user->setRoles(['ROLE_FOURNISSEUR']);
            $userRepository->add($user);
            $fourni = new Fournisseur();
            $fourni->setIdUser($user);
            $fourni->setCin("NULL");
            $fourni->setnomMarque("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($fourni);
            $em->flush();
        } else if ($role == "client") {
            $user->setRoles(['ROLE_CLIENT']);

            $userRepository->add($user);
            $client = new Client();
            $client->setIdUser($user);
            $client->setSexe("NULL");

            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
        }

        return new Response('User ajouté avec succés JSON!');
    }

    /**
     * @Route ("/edit/{id}/{email}/{nom}/{prenom}/{role}", name="add")
     */
    public function edit(Request $request, UserRepository $userRepository, SerializerInterface $serializerInterface, EntityManagerInterface $em, $id, $email, $nom, $prenom, $role): Response
    {
        $user = $userRepository->find($id);
        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setRole($role);
        if ($role == "administrateur") {
            $user->setRoles(['ROLE_ADMIN']);
            $userRepository->add($user);
        } else if ($role == "livreur") {
            $user->setRoles(['ROLE_LIVREUR']);
            $userRepository->add($user);
        } else if ($role == "fournisseur") {
            $user->setRoles(['ROLE_FOURNISSEUR']);
            $userRepository->add($user);
        } else if ($role == "client") {
            $user->setRoles(['ROLE_CLIENT']);
            $userRepository->add($user);
        }

        return new Response('User modifié avec succés JSON!');
    }
    /**
     * @Route("/deleteUser/{id}", name="deleteUser")
     */
    public function deleteUser(Request $request, NormalizerInterface $normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(User::class)->find($id);
        $em->remove($produit);
        $em->flush();
        $jsonContent = $normalizer->normalize($produit, 'json', ['groups' => 'productsgroup']);
        return new Response("user supprimé avec succées" . json_encode($jsonContent));

    }

    /**
     * @Route("/editUser/{id}", name="editUser")
     */
    public function editUser(Request $req, NormalizerInterface $normalizer, SerializerInterface $serializerInterface, UserRepository $userRepository, $id): Response
    {
        $content = $req->getContent();
        $request = $serializerInterface->deserialize($content, User::class, 'json');
        $user = $userRepository->find($id);
        $user->setEmail($request->getEmail());
        $user->setNom($request->getNom());
        $user->setPrenom($request->getPrenom());
        $user->setAdresse($request->getAdresse());
        $user->setRole($request->getRole());
        if ($request->getRole() == "administrateur") {
            $user->setRoles(['ROLE_ADMIN']);
            $userRepository->add($user);

            $admin = new Administrateur();
            $admin->setIdUser($user);
            $admin->setCin("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();
        } else if ($request->getRole() == "livreur") {
            $user->setRoles(['ROLE_LIVREUR']);
            $userRepository->add($user);
            $livreur = new Livreur();
            $livreur->setIdUser($user);
            $livreur->setCin("NULL");
            $livreur->setVehicule("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();
        } else if ($request->getRole() == "fournisseur") {
            $user->setRoles(['ROLE_FOURNISSEUR']);
            $userRepository->add($user);
            $fourni = new Fournisseur();
            $fourni->setIdUser($user);
            $fourni->setCin("NULL");
            $fourni->setnomMarque("NULL");
            $em = $this->getDoctrine()->getManager();
            $em->persist($fourni);
            $em->flush();
        } else if ($request->getRole() == "client") {
            $user->setRoles(['ROLE_CLIENT']);
            $userRepository->add($user);
            $client = new Client();
            $client->setIdUser($user);
            $client->setSexe("NULL");

            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
        }

        $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'productsgroup']);
        return new Response("user modifié avec succées" . json_encode($jsonContent));

    }

    /**
     * @Route("/login/{email}/{password}", name="login")
     */
    public function login(UserPasswordEncoderInterface $encoder, Request $req, NormalizerInterface $normalizer, SerializerInterface $serializerInterface, UserRepository $userRepository, $email, $password): Response
    {

        $user = $userRepository->findOneByEmail($email);
        

        if ($user == null) {
            return new Response("Email incorrect");
        } else {
            
            if ($encoder->isPasswordValid($user, $password)) {
                $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'productsgroup']);
                return new Response(json_encode($jsonContent));
            } else {
             
                return new Response("Mot de passe incorrect");
            }

           
            return new Response("Email incorrecte");

        }

    }
     

     /**
     * @Route("/passwordReset/{email}/{password}", name="reset")
     */
    public function ResetPassowrd(UserPasswordEncoderInterface $encoder, Mailer $mailer,Request $req, NormalizerInterface $normalizer, SerializerInterface $serializerInterface, UserRepository $userRepository, $email, $password): Response
    {
         $user=$userRepository->findOneByEmail($email);  
         $user->setPassword(
            $encoder->encodePassword(
                $user,
                $password
            )
          );
        $userRepository->add($user);
            return new Response("Mot de passe  modifié");
    }
   
    /**
     * @Route("/inscri/{email}/{password}/{nom}/{prenom}/{adresse}/{tel}/{date}", name="inscri")
     */
    public function Inscription(UserPasswordEncoderInterface $encoder, Mailer $mailer,Request $req, NormalizerInterface $normalizer, SerializerInterface $serializerInterface, UserRepository $userRepository, $email, $password, $nom, $prenom, $adresse, $tel, $date): Response
    {
        $user = new User();
        $user->setPassword(
            $encoder->encodePassword(
                $user,
                $password
            )
          );
        //  $f= substr($file, strrpos($file, '/' )+1)."\n";
          $user->setEmail($email);
          $user->setNom($nom);
          $user->setPrenom($prenom);
          $user->setAdresse($adresse);
          $user->setTel($tel);
          $d = new \DateTime($date);
          $user->setDns($d);
          $user->setRole("client");
          $user->setRoles(['ROLE_CLIENT']);
          $user->setImage("avatar-user.jpg");
          $userRepository->add($user);
          return new Response("User ajouter");

        
    }
  
    
}
