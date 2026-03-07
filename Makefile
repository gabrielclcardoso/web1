up:
	docker compose up -d

up-nocache:
	docker compose build --no-cache
	docker compose up -d

down:
	docker compose down

fclean:
	docker compose down -v

re: fclean up-nocache

.PHONY: up up-nocache down fclean re
