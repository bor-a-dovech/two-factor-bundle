<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Generator;

use Symfony\Component\Security\Core\User\UserInterface;

class YandexKeyGenerator implements GeneratorInterface
{
    public function generateCode(UserInterface $user) : ?string
    {
        return null;
    }
}