<?php

namespace Pantheon\TwoFactorBundle\Service\User\Repository;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserRepositoryInterface
{
    public function getUser(string $username) : ?UserInterface;
}