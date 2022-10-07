<?php

namespace Pantheon\TwoFactorBundle\CodeRepository;

use Symfony\Component\Security\Core\User\UserInterface;

class NullRepository implements CodeRepositoryInterface
{
    public function load(UserInterface $user) : ?string
    {
        return null;
    }

    public function save(string $code, UserInterface $user) : void
    {
    }
}