<?php

namespace Pantheon\TwoFactorBundle\Service\Code;


use Symfony\Component\Security\Core\User\UserInterface;

class DayOfWeekGenerator implements GeneratorInterface
{
    public function generateCode(UserInterface $user) : ?string
    {
        return null;
    }
}