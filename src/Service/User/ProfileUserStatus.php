<?php

namespace Pantheon\TwoFactorBundle\Service\User;

use Symfony\Component\Security\Core\User\UserInterface;

class ProfileUserStatus implements UserStatusInterface
{
    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isAuthenticatedPartially(UserInterface $user) : bool
    {
        return ($user->isAuthenticatedPartially());
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isAuthenticatedFully(UserInterface $user) : bool
    {
        return !$user->isAuthenticatedPartially();
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function setAuthenticatedPartially(UserInterface $user) : void
    {
        $user->setAuthenticatedPartially();
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function setAutheticatedFully(UserInterface $user) : void
    {
        $user->setAuthenticatedPartially(false);
        $this->em->persist($user);
        $this->em->flush();
    }
}