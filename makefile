migrate-fresh-seed:
	php artisan migrate:fresh
	php artisan db:seed

cur:
	php artisan migrate:fresh
	php artisan db:seed --class=StudentSeeder

#run from local
ssh-web:
	docker exec -it code_ss_web bash

ssh-redis:
	docker exec -it code_ss_web redis-cli
