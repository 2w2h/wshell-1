build:
	@export PUID=$(id -u $USER)
	@export PGID=$(id -g $USER)
	@docker-compose build --build-arg $PUID --build-arg $PGID
enter:
	@docker exec -it dc_php_1 bash
start:
	@docker-compose up -d
restart:
	@docker-compose stop && docker-compose start
stop:
	@docker-compose stop
install:
	@docker exec -it dc_php_1 bash
	@cd wshell && composer install
