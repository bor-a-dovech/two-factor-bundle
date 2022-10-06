<?php

namespace Pantheon\TwoFactorBundle\Service\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * TODO:
 */
class ProfileUserStatus implements UserStatusInterface
{
    /**
     * @param User $user
     * @return bool
     */
    public function isAuthenticatedPartially(UserInterface $user) : bool
    {
        return ($user->getComment() == 'authenticated_partially');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAuthenticatedFully(UserInterface $user) : bool
    {
        return (($user) and (!$user->getComment()));
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function setAuthenticatedPartially(UserInterface $user) : void
    {
        $user->setComment('authenticated_partially');
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     * @return void
     */
    public function setAutheticatedFully(UserInterface $user)
    {
        $user->setComment(null);
        $this->em->persist($user);
        $this->em->flush();
    }
}