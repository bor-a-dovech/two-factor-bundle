<?php

namespace Pantheon\TwoFactorBundle\Service\Code;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Отослать код пользователю.
 */
interface SenderInterface
{
    public function sendCode(?string $code, UserInterface $user) : void;
}