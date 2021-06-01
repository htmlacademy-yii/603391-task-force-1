FROM php:8.0.6-fpm
RUN apt-get update
RUN docker-php-ext-install pdo_mysql
ADD ./docker/php/local.ini usr/local/etc/php/conf.d/local.ini
WORKDIR /app
EXPOSE 9000
