<?php

namespace Pantheon\TwoFactorBundle\Manager;

use App\TwoFactor\Domain\Exception\SendCodeException;
use App\TwoFactor\Provider\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Pantheon\UserBundle\Entity\User;

class TwoFactorManager
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
        ProviderInterface $provider
    )
    {
        $this->em = $em;
        $this->provider = $provider;
    }

    /**
     * @param User $user
     * @return void
     * @throws SendCodeException
     */
    public function sendCode(User $user) : void
    {
        try {
            $this->provider->sendCode($user);
        } catch (\Throwable $e) {
            throw new SendCodeException($e->getMessage());
        }
    }

    /**
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function isCodeValid(User $user, string $code) : bool
    {
        return $this->provider->isCodeValid($user, $code);
    }

    /**
     * @return bool
     */
    public function isTwoFactorAuthenticationEnabled() : bool
    {
        return $_ENV['USE2FACTOR'] ?? false;
    }

    /**
     * @param User $user
     * @return void
     */
    public function setAuthenticatedPartially(User $user)
    {
        $user->setComment('authenticated_partially');
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     * @return void
     */
    public function setAutheticatedFully(User $user)
    {
        $user->setComment(null);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAuthenticatedPartially(User $user) : bool
    {
        return ($user->getComment() == 'authenticated_partially');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAuthenticatedFully(User $user) : bool
    {
        return (($user) and (!$user->getComment()));
    }
}