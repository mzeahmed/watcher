RED=\033[0;31m
GREEN=\033[0;32m
YELLOW=\033[0;33m
BLUE=\033[0;34m
NO_COLOR=\033[0m

w: ## watch assets
	@echo "$(BLUE)Watching assets...$(NO_COLOR)"
	npm run watch

b: ## build assets
	@echo "$(BLUE)Building assets...$(NO_COLOR)"
	npm run build
	@echo "$(GREEN)Assets built!$(NO_COLOR)"

i18n: ## Generating pot file
	@echo "$(BLUE)Generationg pot file...$(NO_COLOR)"
	php ./bin/wp i18n make-pot . resources/i18n/watcher.pot
	@echo "$(GREEN)Pot file generated!$(NO_COLOR)"

pint: ## Lance Pint en mode test
	@echo "$(YELLOW)Lancement de Pint...$(NO_COLOR)"
	composer run lint
	@echo "$(GREEN)Pint terminé$(NO_COLOR)"

pintf: ## Lance Pint avec correction
	@echo "$(YELLOW)Lancement de Pint avec correction...$(NO_COLOR)"
	composer run lint:fix
	@echo "$(GREEN)Pint terminé$(NO_COLOR)"
