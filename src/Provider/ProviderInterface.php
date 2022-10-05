<?php

namespace Pantheon\TwoFactorBundle\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

interface ProviderInterface
{
    /**
     * @param UserInterface $user
     * @return void
     */
    public function sendCode(UserInterface $user) : void;

    /**
     * @param UserInterface $user
     * @param string $code
     * @return bool
     */
    public function isCodeValid(UserInterface $user, string $code) : bool;
}