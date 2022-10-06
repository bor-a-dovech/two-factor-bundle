<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Sender;

use Pantheon\TwoFactorBundle\Repository\NullRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class DayOfWeekSender implements SenderInterface
{
    public function sendCode(?string $code, UserInterface $user) : void
    {
    }
}