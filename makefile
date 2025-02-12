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