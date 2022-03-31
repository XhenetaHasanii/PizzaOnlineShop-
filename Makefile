start:
	docker-compose up -d

console:
	docker exec -it ewa_docker_php-apache_1 bash

stop:
	docker-compose down

build:
	docker-compose down -v
	docker-compose build
	docker-compose up -d --force-recreate mariadb
	docker-compose up -d

clean:
	docker-compose down -v
	docker rmi ewa_docker_php-apache
	docker rmi mariadb
	docker rmi phpmyadmin/phpmyadmin

