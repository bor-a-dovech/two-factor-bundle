<?php

namespace Pantheon\TwoFactorBundle\Security;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Pantheon\TwoFactorBundle\Exception\IsAuthenticatedPartiallyException;
use Pantheon\TwoFactorBundle\Service\User\Repository\UserRepositoryInterface;
use Pantheon\TwoFactorBundle\Service\User\UserStatusInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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

    private string $loginRoute;
    private string $loginSuccessRoute;
    private UrlGeneratorInterface $urlGenerator;
    private TokenStorageInterface $tokenStorage;
    private UserRepositoryInterface $userRepository;
    private UserStatusInterface $userStatusService;

    public function __construct(
        string $loginRoute,
        string $loginSuccessRoute,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage,
        UserRepositoryInterface $userRepository,
        UserStatusInterface $userStatusService
    )
    {
        $this->loginRoute = $loginRoute;
        $this->loginSuccessRoute = $loginSuccessRoute;
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
        $this->userStatusService = $userStatusService;
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');
        $session = $request->getSession();
        $session->set(Security::LAST_USERNAME, $username);
        $user = $this->userRepository->getUser($username);
        if (!$this->userStatusService->hasAuthenticatedStatus($user)) {
            $this->userStatusService->setAuthenticatedPartially($user);
        }
        $passport = new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new TwoFactorBadge($user, $this->userStatusService)
            ]
        );
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->urlGenerator->generate($this->loginSuccessRoute));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate($this->loginRoute);
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
