## 1. If necessary, update the paths in the `docker-compose.yml` file.

## 2. Navigate to the root directory (`/`) and run the following commands:

```bash
docker-compose build
docker-compose up -d
```

## 3. Check whether the containers are running:

```bash
docker ps -a
```

The output should look like this:

```bash
c641f3a91181   nginx:1.13-alpine      "nginx -g 'daemon of…"   10 hours ago   Up 10 hours   0.0.0.0:8888->80/tcp     test_nginx
953d9acbc614   php:8.2.1-fpm          "docker-php-entrypoi…"   10 hours ago   Up 10 hours   0.0.0.0:9000->9000/tcp   test_php
81fe68292b66   postgres:14.7-alpine   "docker-entrypoint.s…"   10 hours ago   Up 10 hours   0.0.0.0:5432->5432/tcp   test_postgres
```

## 4. Access the PHP container's bash shell:

```bash
docker exec -it test_php bash
```

## 5. Install all dependencies:

```bash
composer install
```

## 6. Copy `.env.example` to `.env`.

## 7. Run the database migrations:

```bash
php artisan migrate
```

## 8. Open the following URL in your browser:

```text
http://localhost:8888/files
```
