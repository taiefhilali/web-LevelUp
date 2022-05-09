<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, FlashyNotifier $flashy)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->flashy = $flashy;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );
        if (!empty($this->userRepository->findByEmail($request->request->get('email')))) {
            $user = $this->userRepository->findByEmail($request->request->get('email'))[0];
            $request->getSession()->set('id', $user->getIdUser());
            $request->getSession()->set('email', $request->request->get('email'));
            $request->getSession()->set('nom', $user->getNom());
            $request->getSession()->set('prenom', $user->getPrenom());
            $request->getSession()->set('role', $user->getRole());
            $request->getSession()->set('password', $user->getPassword());
            $request->getSession()->set('image', $user->getImage());
        }

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            throw new UsernameNotFoundException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey )
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        $user = $this->userRepository->findByEmail($request->request->get('email'))[0];
        if($user->getActivationToken() == "NULL"){
            if (!empty($this->userRepository->findByEmail($request->request->get('email')))) {
                $user = $this->userRepository->findByEmail($request->request->get('email'))[0];
                $request->getSession()->set('id', $user->getIdUser());
                $request->getSession()->set('password', $user->getPassword());
                $request->getSession()->set('email', $request->request->get('email'));
                $request->getSession()->set('nom', $user->getNom());
                $request->getSession()->set('prenom', $user->getPrenom());
                $request->getSession()->set('role', $user->getRole());
                $request->getSession()->set('image', $user->getImage());
            }
            if ($user->getRole()=="client")
            {
                return new RedirectResponse($this->urlGenerator->generate('app_accountClient'));
                throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
            }
     
                    
        }
        else{
        
            $this->flashy->error('Activez votre compte!', 'http://your-awesome-link.com');
            throw new UsernameNotFoundException('Activez votre compte');
        }
        return new RedirectResponse($this->urlGenerator->generate('app_account'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
