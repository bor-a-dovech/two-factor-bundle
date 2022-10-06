<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Generator;


use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Сгенерировать код, который будет использоваться для подтверждения данного юзера.
 */
interface GeneratorInterface
{
    public function generate(UserInterface $user) : ?string;

    public function isValid(string $code, UserInterface $user) : bool;
}