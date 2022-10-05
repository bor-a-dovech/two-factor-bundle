<?php

namespace Pantheon\TwoFactorBundle\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class IsAuthenticatedPartiallyException extends CustomUserMessageAuthenticationException
{

}
