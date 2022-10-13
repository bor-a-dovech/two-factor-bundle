# two-factor-bundle
Бандл для двухфакторной аутентификации

## Установка

Composer.json:
```json
{
    ...
      "repositories": [
        {
          "type": "vcs",
          "url": "https://github.com/bor-a-dovech/two-factor-bundle.git"
        }
      ]
   ...
}
```
Добавление бандла:
```bash
composer require pantheon/two-factor-bundle
```

#### Конфигурирование бандла:

##### config/routes/two-factor-bundle.yaml
```yaml
two-factor-bundle:
  resource: ../../vendor/pantheon/two-factor-bundle/src/Controller/
  type: annotation
```

##### config/services.yaml
```yaml
services:
  Pantheon\TwoFactorBundle\Manager\TwoFactorManager:
    arguments:
    $isTwoFactorAuthenticationAvailable: '%env(IS_TWO_FACTOR_AUTHENTICATON_AVAILABLE)%'

  Pantheon\TwoFactorBundle\Security\TwoFactorAuthenticator:
    arguments:
      $loginRoute: '%env(LOGIN_ROUTE)%'
      $loginSuccessRoute: '%env(LOGIN_SUCCESS_ROUTE)%'

  Pantheon\TwoFactorBundle\Service\Code\Generator\GeneratorInterface: '@Pantheon\TwoFactorBundle\Service\Code\Generator\DayOfWeekGenerator'
  Pantheon\TwoFactorBundle\Service\Code\Sender\SenderInterface: '@Pantheon\TwoFactorBundle\Service\Code\Sender\DayOfWeekSender'
  Pantheon\TwoFactorBundle\Service\Code\Storager\StoragerInterface: '@Pantheon\TwoFactorBundle\Service\Code\Storager\DayOfWeekStorager'
  Pantheon\TwoFactorBundle\Service\Code\Validator\ValidatorInterface: '@Pantheon\TwoFactorBundle\Service\Code\Validator\DayOfWeekValidator'

  Pantheon\TwoFactorBundle\Service\User\UserStatusInterface: '@Pantheon\TwoFactorBundle\Service\User\ProfileUserStatus'
```


##### .env
```txt
IS_TWO_FACTOR_AUTHENTICATON_AVAILABLE=true
LOGIN_ROUTE=app_login
LOGIN_SUCCESS_ROUTE=front
```
