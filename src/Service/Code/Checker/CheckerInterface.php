<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Checker;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Проверить полученный от пользователя код.
 */
interface CheckerInterface
{
    public function isCodeValid(string $code, UserInterface $user) : bool;
}