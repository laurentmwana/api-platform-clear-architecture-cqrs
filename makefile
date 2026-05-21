PHP=php
COMPOSER=composer

.DEFAULT_GOAL := help

## —— Help ————————————————————————————————————————————————
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "%-20s %s\n", $$1, $$2}'

## —— Install ———————————————————————————————————————————————
install: ## Install dependencies
	$(COMPOSER) install --prefer-dist --no-interaction --no-progress --optimize-autoloader

## —— Symfony Lint ——————————————————————————————————————————
lint-yaml: ## Lint YAML files
	$(PHP) bin/console lint:yaml config

lint-container: ## Lint Symfony container
	$(PHP) bin/console lint:container

lint-twig: ## Lint Twig templates
	$(PHP) bin/console lint:twig templates


stan: ## PHPStan static analysis
	vendor/bin/phpstan analyse

cs: ## Coding style (ECS)
	vendor/bin/ecs check

rector: ## Rector dry-run
	vendor/bin/rector process --dry-run

## —— Tests ————————————————————————————————————————————————
test: ## Run PHPUnit tests
	vendor/bin/phpunit

## —— CI ————————————————————————————————————————————————
ci: install lint-yaml lint-container lint-twig stan cs rector ## Full pipeline

## —— Cleanup ————————————————————————————————————————————
clean: ## Clean cache
	rm -rf var/cache/*
