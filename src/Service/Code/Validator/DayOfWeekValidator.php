<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Validator;

use Symfony\Component\Security\Core\User\UserInterface;

class DayOfWeekValidator implements ValidatorInterface
{
    public function isCodeValid(string $code, UserInterface $user) : bool
    {
        return strtolower($code) == strtolower(date("l"));
    }
}