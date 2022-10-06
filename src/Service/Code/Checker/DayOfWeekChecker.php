<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Checker;

use Symfony\Component\Security\Core\User\UserInterface;

class DayOfWeekChecker implements CheckerInterface
{
    public function isCodeValid(string $code, UserInterface $user) : bool
    {
        return strtolower($code) == strtolower(date("l"));
    }
}