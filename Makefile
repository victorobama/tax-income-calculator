.PHONY: build up down restart logs shell

# Default build command
build:
	@echo "Building and starting containers..."
	docker-compose up -d --build

# Start containers
up:
	@echo "Starting containers..."
	docker-compose up -d

# Stop containers
down:
	@echo "Stopping containers..."
	docker-compose down

# Restart containers
restart:
	@echo "Restarting containers..."
	docker-compose restart

# Show logs
logs:
	@echo "Showing logs..."
	docker-compose logs -f

# Shell into PHP container
shell:
	@echo "Opening shell in PHP container..."
	docker-compose exec php bash

# Initialize project structure
init:
	@echo "Creating project structure..."
	mkdir -p src/css src/js src/api database nginx php
	touch database/init.sql nginx/default.conf php/Dockerfile
	@echo "Project structure created."
