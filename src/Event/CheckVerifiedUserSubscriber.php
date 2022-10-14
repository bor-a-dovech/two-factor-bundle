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
        if ($this->twoFactorManager->isAuthenticatedPartially($user)) {
            throw new IsAuthenticatedPartiallyException();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
        ];
    }
}