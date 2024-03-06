# The **Jen-storage** Project
## Requirements
- [Laravel 9.x](https://laravel.com/docs/9.x)
- [Docker >= 18.06.1-ce](https://docs.docker.com/install)
- [Docker-compose >= 1.29.0](https://docs.docker.com/compose/install)
- [PHP 8.1](https://www.php.net/downloads.php)
- [Nginx > = nginx/1.15.7](https://www.nginx.com/resources/wiki/start/topics/tutorials/install/)
- [Node >= v14.21.1](https://nodejs.org/en/download/)
- [Yarn >= 1.2.19](https://yarnpkg.com/en/docs/install#debian-stable)
- [PHP_CodeNiffer](https://siderlabs.com/blog/lets-clean-up-our-php-code-using-php-codesniffer-f4f18442a470/)
## Setup
- Copy file `.env.example` to `.env`.
```BASH
cp .env.example .env
```
- Create and start containers:
```BASH
docker-compose up -d
```
- Check all the containers: 
```
docker ps -a
```
## Installation
- Access the Workspace container:
```BASH
docker exec -it jen_workspace bash
```
- Install PHP packages:
```BASH
composer install
```
- Generate a new Application Key:
```
php artisan key:generate
```
- Run this command to run the server
```BASH
php artisan serve
```

## API Docs
- Access website `https://editor.swagger.io/` and import `API_docs.yaml` file to see the API docs 
