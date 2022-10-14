<?php


namespace Pantheon\TwoFactorBundle\Event;

use Pantheon\TwoFactorBundle\Exception\IsAuthenticatedPartiallyException;
use Pantheon\TwoFactorBundle\Manager\TwoFactorManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{

    private TwoFactorManager $twoFactorManager;

    public function __construct(
        TwoFactorManager $twoFactorManager
    )

    {
        $this->twoFactorManager = $twoFactorManager;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();
        if (!$passport instanceof UserPassportInterface) {
            throw new \Exception('Unexpected passport type');
        }
        $user = $passport->getUser();

        // 1. проверка, может ли он ВООБЩЕ быть аунтифи
        if ($this->twoFactorManager->isTwoFactorAuthenticationAvailable()) { // 1. проверка, включено ли в конфиге (широко)
            if ($this->twoFactorManager->isTwoFactorAuthenticationAllowedForUser($user)) { // 2. проверка, включено ли в профиле
                if ($this->twoFactorManager->isAuthenticatedPartially($user)) { // если все включено, и он в середине, бросаем "экзепшон"
                    throw new IsAuthenticatedPartiallyException();
                }
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
        ];
    }
}