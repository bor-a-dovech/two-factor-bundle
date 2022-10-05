<?php


namespace Pantheon\TwoFactorBundle\Event\Subscriber;

use App\TwoFactor\Domain\Exception\IsAuthenticatedPartiallyException;
use Pantheon\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{

    public function __construct(
        TwoFactorManager $twoFactorManager
    )

    {
        $this->twoFactorManager = $twoFactorManager;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        // срабатывает ДО "аунтикейтед суксесс" или "файлюр"
        $passport = $event->getPassport();
        if (!$passport instanceof UserPassportInterface) {
            throw new \Exception('Unexpected passport type');
        }
        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }
        if ($this->twoFactorManager->isAuthenticatedPartially($user)) {
            throw new IsAuthenticatedPartiallyException('bad');
        }
//        else {
//            dump('fully');
//            die();
//        }
//        dump('checking passport', $user, $this->twoFactorManager);
//        die();
//        if (!$user->getIsVerified()) {
//            throw new CustomUserMessageAuthenticationException(
//                'Please verify your account before logging in.'
//            );
//        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
        ];
    }
}