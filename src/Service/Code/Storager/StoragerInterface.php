<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Storager;

use Symfony\Component\Security\Core\User\UserInterface;

interface StoragerInterface
{
    public function save(string $code, UserInterface $user) : void;
}