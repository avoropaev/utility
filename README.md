Commands:

make init - Initialize project or reinitialize

make up - Start docker

make down - Stop docker

make restart - Restart docker

make test - Run all tests

make test-coverage - Make Coverage for all tests

make test-unit - Run unit tests

make test-unit-coverage - Make Coverage for unit tests

make utility-composer-install - Composer install

make utility-assets-install - Assets install

make utility-migrations - Run migrations

make utility-fixtures - Load fixtures

Postgres:

    docker-compose exec utility-postgres psql -U app
PHP:

    docker-compose run --rm utility-php-cli php {command}
Composer:

    docker-compose run --rm utility-php-cli composer {command}

How to deploy:

1) docker login [OPTIONS] [SERVER]

    Instruction: https://docs.docker.com/engine/reference/commandline/login/

2) REGISTRY_ADDRESS={REGISTRY_ADDRESS} IMAGE_TAG={IMAGE_TAG} make build-production

    Example: REGISTRY_ADDRESS=registry.gitlab.com/login/app IMAGE_TAG=0.0.1 make build-production
    
3) REGISTRY_ADDRESS={REGISTRY_ADDRESS} IMAGE_TAG={IMAGE_TAG} make push-production

    Example: REGISTRY_ADDRESS=registry.gitlab.com/login/app IMAGE_TAG=0.0.1 make push-production
    
4) PRODUCTION_HOST={PRODUCTION_HOST} PRODUCTION_KEY_PATH={PRODUCTION_KEY_PATH} PRODUCTION_LOGIN={PRODUCTION_LOGIN} PRODUCTION_PORT={PRODUCTION_PORT} IMAGE_TAG={IMAGE_TAG} UTILITY_APP_SECRET={UTILITY_APP_SECRET} UTILITY_DB_PASSWORD={UTILITY_DB_PASSWORD} UTILITY_MAILER_URL={UTILITY_MAILER_URL} UTILITY_ADMIN_PASSWORD='{UTILITY_ADMIN_PASSWORD}' make deploy-production
    
    Example: PRODUCTION_HOST=127.0.0.1 PRODUCTION_KEY_PATH=secret.pem PRODUCTION_LOGIN=ubuntu PRODUCTION_PORT=22 IMAGE_TAG=0.0.1-beta UTILITY_APP_SECRET=a5d5871b86c8bbef957308b1ec1f8622 UTILITY_DB_PASSWORD=password UTILITY_MAILER_URL=gmail://email@gmail.com:password@smtp.gmail.com UTILITY_ADMIN_PASSWORD='$2y$13$9mLgesIUBrzfaItvbP7yMePpAbJ.3wcE2/1GOwYJ9FEA40JSuIHJi' make deploy-production

