<?php

namespace Pantheon\TwoFactorBundle\Service\User;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SessionUserStatus implements UserStatusInterface
{
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function isAuthenticatedPartially(UserInterface $user) : bool
    {
        return $this->session->get('isUserAuthenticatedPartially') === true;
    }

    public function isAuthenticatedFully(UserInterface $user) : bool
    {
        return $this->session->get('isUserAuthenticatedPartially') === false;
    }

    public function setAuthenticatedPartially(UserInterface $user) : void
    {
        $this->session->set('isUserAuthenticatedPartially', true);
    }

    public function setAutheticatedFully(UserInterface $user) : void
    {
        $this->session->set('isUserAuthenticatedPartially', false);
    }
}