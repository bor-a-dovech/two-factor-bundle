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

##### .env
```txt
IS_TWO_FACTOR_AUTHENTICATON_AVAILABLE=true
LOGIN_ROUTE=app_login
LOGIN_SUCCESS_ROUTE=front
```
