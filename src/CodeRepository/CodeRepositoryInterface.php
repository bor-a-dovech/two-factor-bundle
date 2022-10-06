<?php

namespace Pantheon\TwoFactorBundle\CodeRepository;

use Symfony\Component\Security\Core\User\UserInterface;

interface CodeRepositoryInterface
{
    public function load(UserInterface $user) : ?string;

    public function save(?string $code, UserInterface $user) : void;
}
