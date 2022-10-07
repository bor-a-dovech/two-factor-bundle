<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Generator;

use Symfony\Component\Security\Core\User\UserInterface;

interface GeneratorInterface
{
    public function generate(UserInterface $user) : ?string;
}