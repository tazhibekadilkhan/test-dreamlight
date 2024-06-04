## 1. Если необходимо, отредактируйте пути в файле `docker-compose.yml`.

## 2. Перейдите в директорию / и выполните следующие команды:
   ``` 
   docker-compose build 
   docker-compose up -d
```

## 3. Проверьте, запустились ли контейнеры:
 ``` 
 docker ps -a
 ```

Должно выглядеть так:

```
c641f3a91181   nginx:1.13-alpine      "nginx -g 'daemon of…"   10 hours ago   Up 10 hours   0.0.0.0:8888->80/tcp     test_nginx
953d9acbc614   php:8.2.1-fpm          "docker-php-entrypoi…"   10 hours ago   Up 10 hours   0.0.0.0:9000->9000/tcp   test_php
81fe68292b66   postgres:14.7-alpine   "docker-entrypoint.s…"   10 hours ago   Up 10 hours   0.0.0.0:5432->5432/tcp   test_postgres
```

## 4. Затем перейти в bash контейнера php:
   ```
   docker exec -it test_php bash
   ```

## 5. Поставить все зависимости:
   ``` 
   composer install
   ```

## 6. скопировать .env.example to .env


## 7. Поставить миграции бд:
``` 
php artisan migrate
```

## 8. перейти по ссылке

```
http://localhost:8888/files

```


