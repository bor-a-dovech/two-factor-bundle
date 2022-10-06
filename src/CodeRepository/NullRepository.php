<?php

namespace Pantheon\TwoFactorBundle\CodeRepository;

use Symfony\Component\Security\Core\User\UserInterface;

class NullRepository implements CodeRepositoryInterface
{
    public function getCode(UserInterface $user) : ?string
    {
        return null;
    }

    public function saveCode(?string $code, UserInterface $user) : void
    {
    }
}