<?php
namespace App\Security;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Repository\UserRepository; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\GoogleUser;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(SessionInterface $session,ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router,UserRepository $userRepository)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->userRepository = $userRepository;
        $this->session=$session;
    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->getPathInfo()=='/connect/google/check'&& $request->isMethod('GET');
    }

    public function getCredentials(Request $request)
    {

        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
       
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);

        $email = $googleUser->getEmail();
        

        // 2) do we have a matching user by email?
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);
            if(!$user)
            {   
                $client = new Client();
                $user=new User();
                $user->setEmail($googleUser->getEmail());
                $user->setNom($googleUser->getFirstName());
                $user->setPrenom($googleUser->getLastName());
                $user->setImage($googleUser->getAvatar());
                $user->setRole('client');     
                $user->setRoles(['ROLE_CLIENT']);
                $this->em->persist($user);
                $this->em->flush();
                $client->setIdUser($user);
                $client->setSexe("");            
                $this->em->persist($client);
                $this->em->flush();
                $this->session->set('id', $user->getIdUser());
                $this->session->set('nom', $user->getNom());
                $this->session->set('prenom', $user->getPrenom());
                $this->session->set('email', $user->getEmail());
                $this->session->set('image', $user->getImage());
                $this->session->set('role', $user->getRole());
                $this->session->set('srcImage', "true");
   
             
                        }   
            $this->session->set('id', $user->getIdUser());
            $this->session->set('nom', $user->getNom());
            $this->session->set('prenom', $user->getPrenom());
            $this->session->set('email', $user->getEmail());
            $this->session->set('image', $user->getImage());
            $this->session->set('role', $user->getRole());
            $this->session->set('srcImage',"true");

        

      

        return $user;
    }

    /**
     * @return GoogleClient
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('google');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('app_accountClient');
    
        return new RedirectResponse($targetUrl);
         
    
        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/login');
        
    }

    // ...
}