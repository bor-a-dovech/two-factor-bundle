<?php

namespace Pantheon\TwoFactorBundle\Manager;

use App\TwoFactor\Domain\Exception\SendCodeException;
use App\TwoFactor\Provider\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Pantheon\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

interface TwoFactorManagerInterface
{
    public function sendCode(UserInterface $user) : void;

    public function isCodeValid(string $code, UserInterface $user);

    public function isTwoFactorAuthenticationEnabled();

    public function setAuthenticatedPartially(UserInterface $user);

    public function setAutheticatedFully(UserInterface $user);

    public function isAuthenticatedPartially(UserInterface $user);

    public function isAuthenticatedFully(UserInterface $user) : bool;
}