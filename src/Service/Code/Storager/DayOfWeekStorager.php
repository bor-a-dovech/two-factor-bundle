<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Storager;

use Pantheon\TwoFactorBundle\CodeRepository\NullRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class DayOfWeekStorager implements StoragerInterface
{
    public function __construct(NullRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(string $code, UserInterface $user) : void
    {
        $this->repository->save($code, $user);
    }
}