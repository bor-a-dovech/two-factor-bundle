<?php

namespace App\TwoFactor\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

class SampleProvider implements ProviderInterface
{
    /**
     * @param UserInterface $user
     * @return void
     */
    public function sendCode(UserInterface $user) : void
    {
    }

    /**
     * @param UserInterface $user
     * @param string $code
     * @return bool
     */
    public function isCodeValid(UserInterface $user, string $code) : bool
    {
        $hour = (new \DateTime())->format('H');
        return ($code === $hour);
    }
}