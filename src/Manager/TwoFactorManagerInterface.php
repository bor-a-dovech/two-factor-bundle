<?php

namespace Pantheon\TwoFactorBundle\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

interface TwoFactorManagerInterface
{
    public function sendCode(UserInterface $user) : void;

    public function isCodeValid(string $code, UserInterface $user) : bool;

    public function isTwoFactorAuthenticationAvailable() : bool;

    public function setAuthenticatedPartially(UserInterface $user) : void;

    public function setAutheticatedFully(UserInterface $user) : void;

    public function isAuthenticatedPartially(UserInterface $user) : bool;

    public function isAuthenticatedFully(UserInterface $user) : bool;
}