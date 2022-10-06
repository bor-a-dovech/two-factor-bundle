<?php

namespace Pantheon\TwoFactorBundle\Manager;

use App\TwoFactor\Domain\Exception\SendCodeException;
use App\TwoFactor\Provider\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Pantheon\TwoFactorBundle\Entity\TwoFactorAuthenticableInterface;
use Pantheon\TwoFactorBundle\Service\Code\Checker\CheckerInterface;
use Pantheon\TwoFactorBundle\Service\Code\Generator\GeneratorInterface;
use Pantheon\TwoFactorBundle\Service\Code\Saver\SaverInterface;
use Pantheon\TwoFactorBundle\Service\Code\Sender\SenderInterface;
use Pantheon\TwoFactorBundle\Service\User\UserStatusInterface;
use Pantheon\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class TwoFactorManager implements TwoFactorManagerInterface
{
    public function __construct(
        GeneratorInterface $generator,
        SaverInterface $saver,
        SenderInterface $sender,
        VerificatorInterface $checker,
        UserStatusInterface $userStatus
    )
    {
        $this->generator = $generator;
        $this->saver = $saver;
        $this->sender = $sender;
        $this->checker = $checker;
        $this->userStatus = $userStatus;
    }

    public function sendCode(UserInterface $user) : void
    {
        $code = $this->generator->generateCode($user);
        $this->saver->saveCode($code, $user);
        $this->sender->sendCode($code, $user);
    }

    public function isCodeValid(string $code, UserInterface $user) : bool
    {

        return $this->generator->isCodeValid($code, $user);
    }

    public function isTwoFactorAuthenticationEnabled() : bool
    {
        return $_ENV['IS_TWO_FACTOR_AUTHENTICATON_ENABLED'] ?? false;
    }

    public function isTwoFactorAuthenticationAllowedForUser(UserInterface $user) : bool
    {
        return (($user instanceof TwoFactorAuthenticableInterface) and ($user->isTwoFactorEnabled()));
    }

    public function setAuthenticatedPartially(UserInterface $user) : void
    {
        $this->userStatus->setAuthenticatedPartially($user);
    }

    public function setAutheticatedFully(UserInterface $user) : void
    {
        $this->userStatus->setAutheticatedFully($user);
    }

    public function isAuthenticatedPartially(UserInterface $user) : bool
    {
        return $this->userStatus->isAuthenticatedPartially($user);
    }

    public function isAuthenticatedFully(UserInterface $user) : bool
    {
        return $this->userStatus->isAuthenticatedFully($user);
    }
}