<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Validator;

use Symfony\Component\Security\Core\User\UserInterface;

interface ValidatorInterface
{
    public function isCodeValid(string $code, UserInterface $user) : bool;
}