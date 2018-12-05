## REST-сервис сокращения ссылок
Сервис сокращения ссылок (lumen) в docker-compose сборке  
nginx+php-fpm+mysql+redis+worker(supervisor)

* Lumen 5.7
* Nginx
* PHP 7.1.3
* MySQL 5.7
* Redis
* Supervisor
* Swagger

#### Settings
**.env**
* APP_URL=http://127.0.0.1:7777/ - адрес сервиса
* APP_PORT=7777 - порт

#### Installation
* git clone https://github.com/verahkus/lumen-link-shortener.git
* composer install
* cp .env.example .env
* docker-compose up -d 
* docker-compose exec app php artisan migrate

#### Documentation
./api/documentation  
(storage/api-docs/api-docs.json)

**Responses** - ответы api
* validation.required - ссылка обязательна
* validation.correctness - ссылка не корректна (пример: https://yandex.ru)
* validation.base64key - ошибка формата ключа (base64)
* validation.not_found - ссылка не найдена

**Log submit** - события логирования (storage/logs)
* app.validator_shortLink_fails - ошибка вылидации при сокращении
* app.validator_getLink_fails - ошибка вылидации при получении ссылки
* app.response_shortLink - сокращение ссылки
* app.response_getLink - получение ссылки

**docker-compose**
* docker-compose exec app ./vendor/bin/phpunit - запустить тесты
* docker-compose exec app php artisan migrate:refresh - пересобрать базу
* docker-compose up --build --force-recreate - пересобрать контейнеры