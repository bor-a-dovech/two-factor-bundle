<?php

namespace Pantheon\TwoFactorBundle\Service\Code\Validator;

use lfkeitel\phptotp\Base32;
use lfkeitel\phptotp\Totp;
use Pantheon\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class YandexKeyValidator implements ValidatorInterface
{
    public function isCodeValid(string $code, UserInterface $user) : bool
    {
        if ((!method_exists($user, 'getTwoFactorCode')) or (!$user->getTwoFactorCode())) {
            return false;
        }
        $my = $user->getTwoFactorCode();
        $secret = Base32::decode($my);
        $key = (new Totp())->GenerateToken($secret);
        return $key == $code;
    }
}