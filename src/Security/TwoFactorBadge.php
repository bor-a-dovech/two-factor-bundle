<?php

namespace Pantheon\TwoFactorBundle\Security;

use Pantheon\TwoFactorBundle\Service\User\UserStatusInterface;
use Pantheon\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class TwoFactorBadge implements BadgeInterface
{
    private UserInterface $user;
    private UserStatusInterface $userStatusService;

    public function __construct(
        UserInterface $user,
        UserStatusInterface $userStatusService
    )
    {
        $this->user = $user;
        $this->userStatusService = $userStatusService;
    }

    public function isResolved(): bool
    {
//        return
//        return true;
////        TODO: ???????????????
////
        if ($this->user->isTwoFactorAuthenticationEnabled()) {
            $this->userStatusService->isAuthenticatedFully($this->user);
        } else {
            return true;
        }

    }
}
