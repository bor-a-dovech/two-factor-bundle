<?php

namespace Pantheon\TwoFactorBundle\CodeRepository;

use Symfony\Component\Security\Core\User\UserInterface;

interface CodeRepositoryInterface
{
    public function getCode(UserInterface $user) : ?string;

    public function saveCode(?string $code, UserInterface $user) : void;
}