<?php

namespace Pantheon\TwoFactorBundle\Service\Code;


use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Сгенерировать код, который будет использоваться для подтверждения данного юзера.
 */
interface GeneratorInterface
{
    public function generateCode(UserInterface $user) : ?string;
}