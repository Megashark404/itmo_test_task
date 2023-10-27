up:
	docker-compose up -d
down:
	docker-compose down
restart: down up
init:
	docker-compose up -d
	docker exec itmo_php composer install
	xdg-open http://mysite.local:8590/book