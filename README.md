# [web-server-sample](https://github.com/GIGABOZIK/web-server-sample) 
**Описание репозитория:**
Развертывание веб-сервера с помощью docker-compose.

Полезные возможности:
* Быстрая настройка и развертывание - достаточно просто поменять нужный конфиг и перезапустить docker-compose
* Поддерживается PHP(php-fpm)
* Можно хостить несколько доменов (виртуальные хосты)
* Присутствует сервер БД
* Веб-интерфейс СУБД - PhpMyAdmin

**Утилита**| **Описание**           | **Базовый образ**
:----------|:-----------------------|:-----------------
Nginx      | Веб-сервер             | nginx:alpine
PHP        | Интерпретатор ЯП PHP   | php:fpm-alpine
MariaDB    | Сервер БД              | mariadb
PhpMyAdmin | СУБД с веб-интерфейсом | phpmyadmin

***

Описание файлов (внутри `/src/`):
  * `/apps/` - содержит основные файлы приложений;
      * `domains/` - содержит папки со всеми файлами обслуживаемых доменов;
      * `*` - можно сюда подключить папку для хранения данных/скриптов БД;

  * `/build/` - содержит файлы для сборки и конфигурации стека утилит, монтируемые в нужные контейнеры;

  * `docker-compose.yml` - файл docker-compose для запуска контейнеров;

  * `/running/` - содержит логи, конфиги и другие файлы, необходимые для мониторинга и отладки утилит; создается при запуске, если задано соответствующее монтирование (volumes). (находится в `.gitignore`)

***

## Инструкция

0) Убедиться в наличии *docker* и *docker-compose* на хосте.

### Начальная настройка и проверка работоспособности
1) Скачать текущий репозиторий
2) Запустить docker-compose в директории `/src/`: `docker-compose up -d`
3) После запуска контейнеров можно проверить работу предустановленных доменов (см. `/apps/domains/<имя домена>`):
    URL                      | Директория домена
    :------------------------|:--------------------------
    ip (localhost)           | `/apps/domains/html`
    phpinfo.com, example.com | `/apps/domains/phpinfo.com`
    test1.com                | `/apps/domains/test1.com`

   * !!! Если вы используете несуществующий домен (как в примере), убедитесь, что он добавлен в ваш файл hosts.

4) Проверить работу PhpMyAdmin: `<ip>:8001` (`localhost:8001`)
   * Данные для входа заданы в .env (.env-example)
   * (В целях безопасности не рекомендуется открывать порт PhpMyAdmin глобально, особенно при использовании root-пользователя)

***

### Настройка собственных конфигураций и доменов

1) Поместить папку с проектом домена в директорию /apps/domains/<domain_name> (можно назвать именем домена для удобства)
2) Добавить соответствующий виртуальный хост:
   * В файле `/build/etc/nginx/conf.d/default.conf` добавить секцию server {} и внести в нее необходимые для проекта директивы
3) **Переименовать** `.env-example` (создать копию) в `.env` и **переопределить** значения переменных окружения
   * В `docker-compose.yml` также сменить это наименование в директивах `env_file`
   * (файл `.env` помещен в `.gitignore`)
4) Если необходимы дополнительные расширения для PHP, отредактируйте файл `/build/dockerfiles/php.Dockerfile`
5) Запустить docker-compose
6) Проверить работу домена, открыв его в браузере (+ проверить подключение к БД, если необходимо)

***

### Улучшения планируются
- [ ] Добавить настройки PHP
  - [x] Упростить установку расширений для PHP (необходимо добавить pdo_mysql)
  - [ ] Настроить opcache
- [x] Настроить подключение к БД из PHP-скриптов с использованием единых переменных окружения
- [ ] Настроить CertBot для получения и обновления SSL-сертификатов Let's Encrypt для безопасного обмена данными по HTTPS.

***

## Заметки

```bash
# запускаем все контейнеры, видим stdout всех контейнеров, а для остановки используем Ctrl+C
docker-compose up
# запуск в режиме демона (-d = "detached" mode)
docker-compose up -d
# для остановки используем 
docker-compose stop
# для остановки с удалением контейнеров 
docker-compose down
```
https://docs.docker.com/compose/gettingstarted/

***

***
