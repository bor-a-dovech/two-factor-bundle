<?php

namespace Pantheon\TwoFactorBundle\Security;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class TwoFactorBadge implements BadgeInterface
{
//    private $resolved = false;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isResolved(): bool
    {
//        if ($this->session->get('2factor') === 'is_authenticated_partially') {
//            return false;
//            throw new IsAuthenticatedPartiallyBadgeException('olo');
//        }
        return true;
    }
}
