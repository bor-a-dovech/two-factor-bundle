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

  Pantheon\TwoFactorBundle\Service\Code\Generator\GeneratorInterface: '@Pantheon\TwoFactorBundle\Service\Code\Generator\KekGenerator'
  Pantheon\TwoFactorBundle\Service\Code\Generator\SenderInterface: '@Pantheon\TwoFactorBundle\Service\Code\Generator\DayOfWeekSender'
  Pantheon\TwoFactorBundle\Service\Code\Generator\StoragerInterface: '@Pantheon\TwoFactorBundle\Service\Code\Generator\DayOfWeekStorager'
  Pantheon\TwoFactorBundle\Service\Code\Generator\ValidatorInterface: '@Pantheon\TwoFactorBundle\Service\Code\Generator\DayOfWeekValidator'

  Pantheon\TwoFactorBundle\Service\User\UserStatusInterface: '@Pantheon\TwoFactorBundle\Service\User\ProfileUserStatus'
```


##### .env
```txt
IS_TWO_FACTOR_AUTHENTICATON_AVAILABLE=true
LOGIN_ROUTE=app_login
LOGIN_SUCCESS_ROUTE=front
```
