<?php

namespace Pantheon\TwoFactorBundle\Manager;

use Pantheon\TwoFactorBundle\Entity\TwoFactorAuthenticableInterface;
use Pantheon\TwoFactorBundle\Service\Code\Generator\GeneratorInterface;
use Pantheon\TwoFactorBundle\Service\Code\Storager\StoragerInterface;
use Pantheon\TwoFactorBundle\Service\Code\Sender\SenderInterface;
use Pantheon\TwoFactorBundle\Service\Code\Validator\ValidatorInterface;
use Pantheon\TwoFactorBundle\Service\Expiration\ExpirationInterface;
use Pantheon\TwoFactorBundle\Service\ResendTimer\ResendTimerInterface;
use Pantheon\TwoFactorBundle\Service\User\UserStatusInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TwoFactorManager implements TwoFactorManagerInterface
{
    private bool $isTwoFactorAuthenticationAvailable;
    private GeneratorInterface $generator;
    private StoragerInterface $storager;
    private SenderInterface $sender;
    private ValidatorInterface $validator;
    private UserStatusInterface $userStatusService;
    private SessionInterface $session;
    private ResendTimerInterface $resendTimerService;
    private ExpirationInterface $expirationService;

    public function __construct(
        bool $isTwoFactorAuthenticationAvailable,
        GeneratorInterface $generator,
        StoragerInterface $storager,
        SenderInterface $sender,
        ValidatorInterface $validator,
        UserStatusInterface $userStatusService,
        SessionInterface $session,
        ResendTimerInterface $resendTimerService,
        ExpirationInterface $expirationService

    )
    {
        $this->isTwoFactorAuthenticationAvailable = $isTwoFactorAuthenticationAvailable;
        $this->generator = $generator;
        $this->storager = $storager;
        $this->sender = $sender;
        $this->validator = $validator;
        $this->userStatusService = $userStatusService;
        $this->session = $session;
        $this->resendTimerService = $resendTimerService;
        $this->expirationService = $expirationService;
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function sendCode(UserInterface $user) : void
    {
        $code = $this->generator->generateCode($user);
        if (!is_null($code)) {
            $this->storager->save($code, $user);
            $this->sender->send($code, $user);
        }
        $this->resendTimerService->startResendTimer();
        $this->expirationService->startExpirationTimer();
    }

    /**
     * @param string $code
     * @param UserInterface $user
     * @return bool
     */
    public function isCodeValid(string $code, UserInterface $user) : bool
    {
        return $this->validator->isCodeValid($code, $user);
    }

    /**
     * @return bool
     */
    public function isTwoFactorAuthenticationAvailable() : bool
    {
        return $this->isTwoFactorAuthenticationAvailable;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isTwoFactorAuthenticationAllowedForUser(UserInterface $user) : bool
    {
        // 1. если он не заинтерфейсен, то точно да
        return (($user instanceof TwoFactorAuthenticableInterface)
            ? $user->isTwoFactorAuthenticationEnabled()
            : true
        );
//        if (!($user instanceof TwoFactorAuthenticableInterface)) {
//            return false;
//        }
//
//        // 2. если заинтерфейсен, то смотрим в профиле
//
//        return (($user instanceof TwoFactorAuthenticableInterface) and ($user->isTwoFactorAuthenticationEnabled()));
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function setAuthenticatedPartially(UserInterface $user) : void
    {
        $this->userStatusService->setAuthenticatedPartially($user);
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function setAutheticatedFully(UserInterface $user) : void
    {
        $this->userStatusService->setAutheticatedFully($user);
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isAuthenticatedPartially(UserInterface $user) : bool
    {
        return $this->userStatusService->isAuthenticatedPartially($user);
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isAuthenticatedFully(UserInterface $user) : bool
    {
        return $this->userStatusService->isAuthenticatedFully($user);
    }
}