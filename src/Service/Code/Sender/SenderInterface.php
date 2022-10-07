<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Sender;

use Symfony\Component\Security\Core\User\UserInterface;

interface SenderInterface
{
    public function send(string $code, UserInterface $user) : void;
}