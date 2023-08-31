up: docker-up
down: docker-down
erase: docker-erase
restart: docker-down docker-up
init-dev: init
init: docker-init app-init
docker-init: docker-erase docker-pull docker-build docker-up
app-init: permissions composer-install migration cache-clear

test: test-init test-all
test-init: permissions cache-clear-test db-create-test migration-test

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-build:
	docker-compose build

docker-start:
	docker-compose start

docker-stop:
	docker-compose stop

docker-erase:
	docker-compose down -v --remove-orphans
	docker-compose rm -v -f

docker-pull:
	docker-compose pull

docker-recreate:
	docker-compose rm -f
	docker-compose build --pull
	docker-compose up --force-recreate --no-deps -d

cli-bash:
	docker-compose run --rm --no-deps cli /bin/bash

console:
	docker-compose run --rm cli php bin/console $(c)

permissions:
	docker run --rm -v ${PWD}:/app -w /app alpine rm -rf var/* tmp/* \
		&& mkdir -p var/log var/cache var/tmp tmp/ \
		&& chown -R ${USER}:${USER} var/log var/cache var/tmp tmp/ \
		&& chmod -R 777 var/log var/cache var/tmp tmp/

cache-clear:
	docker-compose run --rm --no-deps cli php bin/console cache:clear

composer-install:
	docker-compose run --rm --no-deps cli composer install --no-scripts

migration:
	docker-compose run --rm cli php bin/console doctrine:migrations:migrate --no-interaction

# TESTS

db-create-test:
	docker-compose run --rm --no-deps cli php bin/console doctrine:database:create --if-not-exists --no-interaction --env=test

schema-create-test:
	docker-compose run --rm --no-deps cli php bin/console doctrine:schema:create --no-interaction --env=test

migration-test:
	docker-compose run --rm --no-deps cli php bin/console doctrine:migrations:migrate --no-interaction --env=test
cache-clear-test:
	docker-compose run --rm --no-deps cli php bin/console cache:clear --env=test
test-all:
	docker-compose run --rm --no-deps cli php bin/phpunit --verbose
