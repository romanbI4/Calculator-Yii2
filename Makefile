# Executables (local)
DOCKER_FOLDER = cd docker;
DOCKER_COMP = $(DOCKER_FOLDER) docker-compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php-fpm

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony-docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up migrate fixtures## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) sh

fixtures:
	@$(PHP_CONT) sh -c "cd backend; php bin/console doctrine:fixtures:load --no-interaction"

migrate:
	@$(PHP_CONT) sh -c "cd backend; php bin/console doctrine:migrations:migrate --no-interaction"

migrate-diff:
	@$(PHP_CONT) sh -c "cd backend; php bin/console doctrine:migrations:diff"

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

## —— FRONT  ——————————————————————————————————————————————————————————————
admin-install:
	$(EXEC_PHP) sh -c "cd admin; npm install"

admin-install-vue:
	$(EXEC_PHP) sh -c "cd admin; npm init vue@latest"

admin-run:
	$(EXEC_PHP) sh -c "cd admin; npm install; npm run dev"

admin-build:
	$(EXEC_PHP) sh -c "cd admin; npm run build"

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf
