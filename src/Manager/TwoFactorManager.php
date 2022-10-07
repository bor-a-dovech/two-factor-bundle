<?php

namespace Pantheon\TwoFactorBundle\Manager;

use App\TwoFactor\Domain\Exception\SendCodeException;
use App\TwoFactor\Provider\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Pantheon\TwoFactorBundle\Entity\TwoFactorAuthenticableInterface;
use Pantheon\TwoFactorBundle\Service\Code\Generator\GeneratorInterface;
use Pantheon\TwoFactorBundle\Service\Code\Storager\StoragerInterface;
use Pantheon\TwoFactorBundle\Service\Code\Sender\SenderInterface;
use Pantheon\TwoFactorBundle\Service\Code\Validator\ValidatorInterface;
use Pantheon\TwoFactorBundle\Service\User\UserStatusInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TwoFactorManager implements TwoFactorManagerInterface
{
    public function __construct(
        bool $isTwoFactorAuthenticationAvaliable,
        GeneratorInterface $generator,
        StoragerInterface $storager,
        SenderInterface $sender,
        ValidatorInterface $validator,
        UserStatusInterface $userStatusService
    )
    {
        $this->isTwoFactorAuthenticationAvaliable = $isTwoFactorAuthenticationAvaliable;
        $this->generator = $generator;
        $this->storager = $storager;
        $this->sender = $sender;
        $this->validator = $validator;
        $this->userStatusService = $userStatusService;
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
    public function isTwoFactorAuthenticationAvaliable() : bool
    {
        return $this->isTwoFactorAuthenticationAvaliable;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isTwoFactorAuthenticationAllowedForUser(UserInterface $user) : bool
    {
        return (($user instanceof TwoFactorAuthenticableInterface) and ($user->isTwoFactorAuthenticationEnabled()));
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