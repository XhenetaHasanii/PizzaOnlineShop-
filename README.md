# PizzaShop Docker Setup
You can simply start several docker containers and you are all set for my PizzaShop.

If you succeed in starting the docker containers you will get:
- a nice way to play and deploy webpages with php, html, css etc.

## Disclaimer
When running docker containers you should be aware that this might expose your computer to some threats. You do this on your own risk! I am not liable for any loss whether direct, indirect, incidental or consequential, arising out of use of this project.

## Install docker

Install the `docker` tools as explained here: https://docs.docker.com/engine/install/

For Linux install `docker-compose` separately: https://docs.docker.com/compose/install/

## Initial Setup

In the root folder `PizzaOnlineShop`, where the `docker-compose.yml` file is located, create a file called `env.txt` in order to assign a root password for your database as environment variable (copy the content from the file `env_example.txt` for the syntax).

## Start of the Containers 
Open a console window and start your local PizzaShop-docker with `docker-compose up -d`. This will take a while when you start it the first time since docker loads and assembles all images (next time it will be much faster!).

Now you should have 3 containers running:
- php-apache: Containing Apache Webserver and PHP
- MariaDB: your database server for SQL
- PHPmyAdmin: web-based application to modify your database 

All files in `src` are linked into the apache-php container, so you can see your changes while developing in that folder. Furthermore this folder contains all examples and demos for the lecture. Everything is set up and deployed automatically.

## Test the Installation

Go to [http://localhost](http://localhost) to check the served code. After the installation you will see the content of the file `index.php` from the src-folder. 

You can also select a file by specifying a path starting from the src-folder the file at the end of the URL.

## Stop the Containers
Call `docker-compose down` to stop the containers.

## Development

To connect to the running mariadb instance use the hostname `mariadb`.
Example for php:

```php
new MySQLi("mariadb", "your_user", "your_secureuserpw", "your_database");
```
For normal access to the database without serious permissions please use the User `public` and the password `public`. 


### PHPmyAdmin

To access `phpmyadmin` go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin). This will forward you to the Docker container of `phpmyamin` at [http://localhost:8081/](http://localhost:8081/)

Use the credentials you have set in the `env.txt` file for `root`. The database will be stored persistently (as long as you do not delete the containers). Nevertheless you should better export new database schemes into a sql-file.

### Major Changes
If you have changed your `env.txt` or if you want to start from scratch you have to delete and recreate the database volume. Be aware that your database entries will be lost!
To do so stop the running containers `docker-compose down` and delete db volume `docker volume rm ewa_mariadb`. 

### Misc
- There is a `Makefile` that includes several usefull calls for docker e.g. you can call `make start` instead of `docker-compose up -d`.

- You will find the Apache Logfiles in the folder `Log` of your src folder. So you can access it from outside of the container.
- There are well known attacks on computers using docker and containers. So here are some basic recommendations for security
  - Please make sure that your firewall is always up and running.
  - In the settings of docker you will find the option `Expose daemon on tcp://localhost:2375 without TLS`. If you do not need this feature you should switch it off for security reasons.
  - Start the containers only when you need it.