<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Saver;

use Pantheon\TwoFactorBundle\Repository\NullRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class DayOfWeekSaver implements SaverInterface
{
    public function __construct(NullRepository $repository)
    {
        $this->repository = $repository;
    }

    public function saveCode(?string $code, UserInterface $user) : void
    {
        $this->repository->saveCode($code, $user);
    }
}