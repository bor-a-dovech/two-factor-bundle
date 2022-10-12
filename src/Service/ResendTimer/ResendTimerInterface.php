<?php

namespace Pantheon\TwoFactorBundle\Service\ResendTimer;

interface ResendTimerInterface
{
    const CODE_RESEND_TIME_DEFAULT = 60;

    public function startResendTimer() : void;

    public function isCodeCanBeResendedNow() : bool;

    public function getRemainingSeconds() : int;
}