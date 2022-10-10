<?php

namespace Pantheon\TwoFactorBundle\Service\User\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository implements UserRepositoryInterface
{
    private ServiceEntityRepositoryInterface $externalUserRepository;

    public function __construct(
        ServiceEntityRepositoryInterface $externalUserRepository
    )
    {
        $this->externalUserRepository = $externalUserRepository;
    }

    public function getUser(string $username) : ?UserInterface
    {
        return $this->externalUserRepository->findOneBy(['username' => $username]);
   }
}