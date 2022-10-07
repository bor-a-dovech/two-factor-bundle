<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Sender;

use Pantheon\TwoFactorBundle\CodeRepository\NullRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class DayOfWeekSender implements SenderInterface
{
    public function send(string $code, UserInterface $user) : void
    {
    }
}