<?php

namespace Pantheon\TwoFactorBundle\Entity;

interface TwoFactorAuthenticableInterface
{
    public function isTwoFactorEnabled() : bool;
}