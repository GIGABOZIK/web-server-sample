# basic-web-server
Шаблон сервера для развертывания с помощью docker-compose

Поддерживается PHP, можно хостить несколько доменов.

## Информация

Список утилит:

Утилита    | Описание               | Базовый образ
:----------|:-----------------------|:--------------
Nginx      | Веб-сервер             | nginx:alpine
PHP        | Интерпретатор ЯП PHP   | php:fpm-alpine
MariaDB    | Сервер БД              | mariadb
PhpMyAdmin | СУБД с веб-интерфейсом | phpmyadmin

***

Описание файлов:
* `/apps/` - содержит основные файлы приложений
  * `domains/` - содержит папки со всеми файлами обслуживаемых доменов
  * `что-то еще` - можно сюда подключить папку для хранения файлов БД, чтобы данные сохранялись при перезапуске соответствующего контейнера

* `/build/` - содержит файлы для сборки и конфигурации стека утилит, предназначенные для копирования 

* `docker-compose.yml` - файл docker-compose для запуска

***

* `/running/` - эта директория содержит логи, конфиги и другие файлы, необходимые для мониторинга и отладки утилит. Создается при запуске и используется при последующих перезапусках (до удаления).

***

## Инструкция

0) Убедиться в наличии docker и docker-compose на хосте.

### Начальная настройка и проверка работоспособности
1) Скачать текущий репозиторий
2) В корне распакованного репозитория создать копию папки `/build/` с именем `/running`, затем запустить docker-compose (команда `docker-compose up`)
* Можно сделать это одной командой:
    * Для Linux:
     ```bash
     sudo cp -r ./build ./running && sudo docker-compose up
     ```

    * Для Windows:
     ```shell
     Copy-Item -Path "./build" -Destination "./running" -Recurse; docker-compose up
     ```

3) После запуска контейнеров можно проверить работу предустановленных доменов (см. `/apps/domains/<имя домена>`)
В соответствии с предустановленным файлом конфигурации (см. `/build/etc/nginx/conf.d/default.conf`):
* ip (localhost)            -> `/apps/domains/html/index.html`
* phpinfo.com | example.com -> `/apps/domains/phpinfo.com/index.php`
* test1.com                 -> `/apps/domains/test1.com/index.html`

!!! Если вы используете несуществующий домен (как в примере), убедитесь, что он добавлен в ваш файл hosts.

4) Проверить работу PhpMyAdmin:
* ip (localhost)    :8001   -> интерфейс PhpMyAdmin
Данные для входа заданы в docker-compose:
* root + example_root_password      -> ROOT
<!-- * example_user + example_password   -> example_database -->

***

### Настройка собственных конфигураций и доменов

1) Поместить папку с проектом домена в директорию /apps/domains/<domain_name> (можно назвать именем домена для удобства)
2) Добавить виртуальный хост:
* В файле `/build/etc/nginx/conf.d/default.conf` добавить секцию server {} и внести в нее необходимые для проекта директивы
3) Запустить docker-compose, предварительно создав копию /build/ (см. Начальную настройку)
4) Проверить работу домена, открыв его в браузере

***

### Улучшения планируются

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

Для полного перезапуска контейнеров в процессе отладки проекта, бывает нужно сбрасывать конфиги отработавших утилит.
Для упрощения предлагаю использовать следующие команды:
* Для Linux:
```bash
sudo docker-compose down && sudo rm -r ./running && sudo cp -r ./build ./running && sudo docker-compose up
```
* Для Windows:
```shell
docker-compose down; rm "./running" -Recurse; Copy-Item -Path "./build" -Destination "./running" -Recurse; docker-compose up
```

***
