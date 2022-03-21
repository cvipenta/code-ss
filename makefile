migrate-fresh-seed:
	php artisan migrate:fresh
	php artisan db:seed


#run from local
ssh-web:
	docker exec -it code_ss_web bash

ssh-redis:
	docker exec -it code_ss_web redis-cli
