wShell на момент 2016 года

## node
Исходники wshell ноды (Бинарник, выполняющий юниты)

## dc/web
web интерфейс - Symfony 3, twig, console wamp router+client.
Есть бандлы Admin / Unit / User / WShell.
В целом, должно быть рабочей версией

## dc/*

Контейнеры-сервисы: nginx / mongo / redis

### bower deps install

    docker run -it --rm -v $(pwd):/usr/src/app dc_node bower install --allow-root -q -s
    docker run -it --rm -v $(pwd):/usr/src/app -e UID=$(id -u) dc_node chown -R $UID .

### mongo backup
in mongo container:

    cd /data/db
    mongodump --db wshell

### routes

    news                       ANY      ANY      ANY    /
    nabla                      ANY      ANY      ANY    /nabla
    term                       ANY      ANY      ANY    /term
    chapters                   ANY      ANY      ANY    /chapters
    doc                        ANY      ANY      ANY    /doc/{book}
    about                      ANY      ANY      ANY    /about
    map                        ANY      ANY      ANY    /map
    observer                   ANY      ANY      ANY    /observer
    login                      ANY      ANY      ANY    /login
    oauth                      ANY      ANY      ANY    /oauth
    github_login               ANY      ANY      ANY    /oauth/github
    vk_login                   ANY      ANY      ANY    /oauth/vk
    login_check                ANY      ANY      ANY    /login_check
    logout                     ANY      ANY      ANY    /logout
    units                      GET      ANY      ANY    /admin/units/{unitId}
    edit_unit                  POST     ANY      ANY    /admin/units/post
    uitest                     ANY      ANY      ANY    /units/uitest
    chain                      ANY      ANY      ANY    /units/chain
    single                     ANY      ANY      ANY    /units/single/{name}
    run                        ANY      ANY      ANY    /units/run/{name}