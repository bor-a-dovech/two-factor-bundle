<?php

namespace Pantheon\TwoFactorBundle\Service\ResendTimer;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ResendTimerService implements ResendTimerInterface
{
    private SessionInterface $session;

    public function __construct(
        SessionInterface $session
    )
    {
        $this->session = $session;
    }

    public function startResendTimer() : void
    {
        $seconds = $_ENV['TWO_FACTOR_CODE_RESEND_TIME'] ?? self::CODE_RESEND_TIME_DEFAULT;
        $codeCanBeResendedAfter = (new \DateTime())->modify('+' . $seconds . ' seconds');
        $this->session->set('codeCanBeResendedAfter', $codeCanBeResendedAfter);
    }

    public function isCodeCanBeResendedNow() : bool
    {
        $codeCanBeResendedAfter = $this->session->get('codeCanBeResendedAfter');
        if (is_null($codeCanBeResendedAfter)) {
            return true;
        }
        $now = new \DateTime();
        return ($now > $codeCanBeResendedAfter);
    }

    public function getRemainingSeconds() : int
    {
        /** @var \DateTime $codeCanBeResendedAfter */
        $codeCanBeResendedAfter = $this->session->get('codeCanBeResendedAfter');
        if (is_null($codeCanBeResendedAfter)) {
            return 0;
        }
        $now = new \DateTime();
        if ($now > $codeCanBeResendedAfter) {
            return 0;
        }
        return $codeCanBeResendedAfter->getTimestamp() - $now->getTimestamp();
    }
}