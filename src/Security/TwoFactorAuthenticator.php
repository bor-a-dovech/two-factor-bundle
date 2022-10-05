<?php

namespace Pantheon\TwoFactorBundle\Security;

use Pantheon\TwoFactorBundle\Exception\IsAuthenticatedPartiallyException;
use Pantheon\TwoFactorBundle\Manager\TwoFactorManager;
use Pantheon\UserBundle\Entity\User;
use Pantheon\UserBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class TwoFactorAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private TwoFactorManager $twoFactorManager;
    private UserRepository $userRepository;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        TwoFactorManager $twoFactorManager,
        UserRepository $userRepository

    )
    {
        $this->userRepository = $userRepository;
        $this->twoFactorManager = $twoFactorManager;
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');

        $session = $request->getSession();
        $session->set(Security::LAST_USERNAME, $username);
//        $session->set('2factor', 'is_authenticated_partially');
        $user = $this->userRepository->findOneBy(['username' => $username]);
        $this->twoFactorManager->setAuthenticatedPartially($user);
        $passport = new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))
            ]
        );
//        $passport->addBadge(new TwoFactorBadge($this->userRepository->findOneBy(['username' => $username])));
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();
//        dump('success', $user);
//        die();

//        if ($user) {
//            if ($user->isPasswordExpired()) {
//                return new RedirectResponse($this->urlGenerator->generate('required_password_change'));
//            }
//            if ($this->twoFactorManager->isTwoFactorAuthenticationEnabled()) {
//                $this->twoFactorManager->setAuthenticatedPartially($user);
//                return new RedirectResponse($this->urlGenerator->generate('two_factor_authentication'));
//            }
//        }

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

         return new RedirectResponse($this->urlGenerator->generate('front'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $session = $request->getSession();
        if ($exception instanceof IsAuthenticatedPartiallyException) {
            $url = $this->urlGenerator->generate('two_factor_authentication');
        } else {
            if ($request->hasSession()) {
                $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            }
            $url = $this->getLoginUrl($request);
        }
        return new RedirectResponse($url);
    }
}
