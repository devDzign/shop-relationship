DC=docker-compose
CONTAINER= php
SF= symfony
CONSOLE= $(SF) console
COMPOSER= $(SF) composer
EXEC= $(DC) exec $(CONTAINER)
PHP = php
CON = $(PHP) bin/console
AWK := $(shell command -v awk 2> /dev/null)

.DEFAULT_GOAL := help
.PHONY: help

help: ## Show this help
ifndef AWK
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
else
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
endif

##
## Project setup
##---------------------------------------------------------------------------
.PHONY: install
.PRECIOUS: .env.local docker-compose.yml

install: ## Process all step in order to setup the projects
install: dstop dup dps composer sstop srun sopen slog

##
## Server
##---------------------------------------------------------------------------
.PHONY: require-files srun slog sstop

srun: ## Run server with command symfony
srun: require-files
	$(SF) server:start -d

sstop: ## Stop server with command symfony
	$(SF) server:stop

slog: ## Show log server with command symfony
	$(SF) server:log

sopen: ## open local website
	$(SF) open:local

sstatus: ## Give all cron status
	$(SF) server:status


slkill: ## Kill process 'make slkill pid=123' for linux
	kill $(pid)

swkill: ## Kill process 'make swkill pid=123' for windows
	Taskkill /PID $(pid) /F

##
## Docker
##---------------------------------------------------------------------------
.PHONY: dup dstop

dup: ## Run docker
dup: require-files
	$(DC) up -d

dstop: ## Stop docker
dstop: require-files
	$(DC) stop

sps: ## Docker ps
dps: require-files
	$(DC) ps
##
## Composer
##---------------------------------------------------------------------------
.PHONY: composer

composer: ## Run composer install
	$(COMPOSER) install

##
## Data base postgres
##---------------------------------------------------------------------------
.PHONY: db-dump db-restore

db-dump: ## Dump data base
	$(SF) run pg_dump --data-only > dump.sql

db-restore: ## Restore data base
	$(SF) run psql < dump.sql


##
## Lunch test for app
##---------------------------------------------------------------------------
.PHONY: unit-test

unit-test: ## Unit Test
	$(PHP) bin/phpunit

##
## Require file
##---------------------------------------------------------------------------
.PHONY: require-files
.PRECIOUS:.env.local

require-files: ## require file
require-files: .env.local

.env.local: .env
	@if [ -f .env.local ]; \
	then\
		echo '\033[1;41m/!\ The .env file has changed. Please check your .env.local file (this message will not be displayed again).\033[0m';\
		touch .env.local;\
		exit 1;\
	else\
		echo cp .env .env.local;\
		cp .env .env.local;\
	fi

##
## Symfony
##---------------------------------------------------------------------------
.PHONY: sf-rmq-msg-cons sf-rmq-manager sf-rmq-watch sf_msg_failed_show sf_msg_failed_retry

sf-rmq-msg-cons: ## Messenger consume async chanel
	$(CONSOLE) messenger:consume async -vv

sf-rmq-manager: ## Open manager rabbitMq in browser guest/guest
	$(SF) open:local:rabbitmq

sf-rmq-watch: ## --watch indique à symfony de redémarer chaque fois qu'il y a un changement
	$(SF) run -d --watch=config,src,template,vendor $(CONSOLE) messenger:consume async

sf_msg_failed_show: ## inspecter les message ayant échoué
	$(CONSOLE) messenger:failed:show

sf_msg_failed_retry: ## relancer les msg
	$(CONSOLE) messenger:failed:retry

my_test: ## test dynamic option 'make my_test toto=test_cool'
ifdef toto
	@echo '$(toto) is defined'
else
	@echo 'no toto around'
endif
