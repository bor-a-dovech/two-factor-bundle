<?php

namespace Pantheon\TwoFactorBundle\Service\Code;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Сохранить код в БД или куда положено. Возможно, заменяемо репозиторием.
 */
interface SaverInterface
{
    public function saveCode(?string $code, UserInterface $user) : void;
}