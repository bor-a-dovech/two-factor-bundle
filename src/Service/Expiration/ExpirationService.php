<?php

namespace Pantheon\TwoFactorBundle\Service\Expiration;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ExpirationService implements ExpirationInterface
{
    private SessionInterface $session;

    public function __construct(
        SessionInterface $session
    )
    {
        $this->session = $session;
    }

    public function startExpirationTimer() : void
    {
        $seconds = $this->getExpirationTime();
        $codeExpiresAfter = (($seconds)
            ? (new \DateTime())->modify('+' . $seconds . ' seconds')
            : false
        );
        $this->session->set('codeExpiresAfter', $codeExpiresAfter);
    }

    public function isCodeExpired() : bool
    {
        $codeExpiresAfter = $this->session->get('codeExpiresAfter');
        if (!$codeExpiresAfter) {
            return false;
        }
        $now = new \DateTime();
        return ($now > $codeExpiresAfter);
    }

    public function getExpirationTime() : string
    {
        return $_ENV['TWO_FACTOR_CODE_EXPIRATION_TIME'] ?? self::CODE_EXPIRATION_TIME_DEFAULT;
   }
}