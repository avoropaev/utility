up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear utility-clear docker-pull docker-build docker-up utility-init
test: utility-test
test-coverage: utility-test-coverage
test-unit: utility-test-unit
test-unit-coverage: utility-test-unit-coverage

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

utility-init: utility-composer-install utility-assets-install utility-wait-db utility-migrations utility-fixtures utility-ready

utility-ready:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine touch .ready

utility-clear:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine rm -f .ready

utility-composer-install:
	docker-compose run --rm utility-php-cli composer install

utility-assets-install:
	docker-compose run --rm utility-node yarn install
	docker-compose run --rm utility-node npm rebuild node-sass

utility-migrations:
	docker-compose run --rm utility-php-cli php bin/console doctrine:migrations:migrate --no-interaction

utility-fixtures:
	docker-compose run --rm utility-php-cli php bin/console doctrine:fixtures:load --no-interaction

utility-test:
	docker-compose run --rm utility-php-cli php bin/phpunit

utility-test-coverage:
	docker-compose run --rm utility-php-cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

utility-test-unit:
	docker-compose run --rm utility-php-cli php bin/phpunit --testsuite=unit

utility-test-unit-coverage:
	docker-compose run --rm utility-php-cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage

utility-wait-db:
	until docker-compose exec -T utility-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

build-production:
	docker build --pull --file=docker/production/nginx.docker --tag ${REGISTRY_ADDRESS}/nginx:${IMAGE_TAG} .
	docker build --pull --file=docker/production/php-fpm.docker --tag ${REGISTRY_ADDRESS}/php-fpm:${IMAGE_TAG} .
	docker build --pull --file=docker/production/php-cli.docker --tag ${REGISTRY_ADDRESS}/php-cli:${IMAGE_TAG} .
	docker build --pull --file=docker/production/postgres.docker --tag ${REGISTRY_ADDRESS}/postgres:${IMAGE_TAG} .

push-production:
	docker push ${REGISTRY_ADDRESS}/nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/php-cli:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/postgres:${IMAGE_TAG}

deploy-production:
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} 'rm -rf docker-compose.yml .env'
	scp -o StrictHostKeyChecking=no -i ${PRODUCTION_KEY_PATH} -P ${PRODUCTION_PORT} docker-compose-production.yml ${PRODUCTION_LOGIN}@${PRODUCTION_HOST}:docker-compose.yml
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} "echo IMAGE_TAG='${IMAGE_TAG}' >> .env"
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} "echo UTILITY_APP_SECRET='${UTILITY_APP_SECRET}' >> .env"
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} "echo UTILITY_DB_PASSWORD='${UTILITY_DB_PASSWORD}' >> .env"
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} "echo UTILITY_MAILER_URL='${UTILITY_MAILER_URL}' >> .env"
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} 'docker-compose pull'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} 'docker-compose up --build -d'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} 'until docker-compose exec -T utility-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -l ${PRODUCTION_LOGIN} -i ${PRODUCTION_KEY_PATH} -p ${PRODUCTION_PORT} 'docker-compose run --rm utility-php-cli php bin/console doctrine:migrations:migrate --no-interaction'
