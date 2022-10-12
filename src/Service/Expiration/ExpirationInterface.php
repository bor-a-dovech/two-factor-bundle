<?php

namespace Pantheon\TwoFactorBundle\Service\Expiration;

interface ExpirationInterface
{
    const CODE_EXPIRATION_TIME_DEFAULT = 600;

    public function startExpirationTimer() : void;

    public function isCodeExpired() : bool;

    public function getExpirationTime() : string;
}