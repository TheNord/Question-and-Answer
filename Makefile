docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up --build -d

test:
	docker-compose exec php-cli vendor/bin/phpunit

assets-install:
	docker-compose exec node yarn install

assets-rebuild:
	docker-compose exec node npm rebuild node-sass --force

assets-dev:
	docker-compose exec node yarn run dev

assets-watch:
	docker-compose exec node yarn run watch

perm:
	sudo chgrp -R www-data storage bootstrap/cache
	sudo chmod -R ug+rwx storage bootstrap/cache
	sudo chgrp -R www-data storage storage/app/public/banners
	sudo chmod -R ug+rwx storage storage/app/public/banners

migrate:
	docker-compose exec php-cli php artisan migrate

queue:
	docker-compose exec php-cli php artisan queue:work

horizon:
	docker-compose exec php-cli php artisan horizon

clear-redis:
	docker exec -it question_redis_1_1a309afd4ceb redis-cli FLUSHALL

websockets-start:
	docker-compose exec php-fpm php artisan websockets:serve
