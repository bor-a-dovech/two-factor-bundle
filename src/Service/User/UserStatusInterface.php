<?php

namespace Pantheon\TwoFactorBundle\Service\User;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserStatusInterface
{
    public function isAuthenticatedPartially(UserInterface $user) : bool;
    public function isAuthenticatedFully(UserInterface $user) : bool;
    public function setAuthenticatedPartially(UserInterface $user) : void;
    public function setAutheticatedFully(UserInterface $user) : void;
}