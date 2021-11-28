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

    WshellBundle
    news                         ANY    /
    nabla                        ANY    /nabla
    term                         ANY    /term
    chapters                     ANY    /chapters
    doc                          ANY    /doc/{book}
    about                        ANY    /about
    map                          ANY    /map
    observer                     ANY    /observer

    UserBundle +
    login                        ANY    /login
    oauth                        ANY    /oauth
    github_login                 ANY    /oauth/github
    vk_login                     ANY    /oauth/vk
    login_check                  ANY    /login_check
    logout                       ANY    /logout

    AdminBundle
    units                        GET    /admin/units/{unitId}
    edit_unit                   POST    /admin/units/post

    UnitBundle
    uitest                       ANY    /units/uitest
    chain                        ANY    /units/chain
    single                       ANY    /units/single/{name}
    run                          ANY    /units/run/{name}