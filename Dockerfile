FROM php:8.2.1-fpm
RUN apt-get update
RUN apt-get install -qq git curl libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev libzip-dev libpq-dev unzip
RUN apt-get clean
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install zip pdo_pgsql pdo_mysql bcmath bz2 gd
RUN curl --silent --show-error "https://getcomposer.org/installer" | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer global require "laravel/envoy=~1.0"
RUN chown -R www-data:www-data /var/www

