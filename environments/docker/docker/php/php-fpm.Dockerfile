FROM php:8.0.2-fpm
RUN docker-php-ext-install pdo_mysql
RUN apt-get update
RUN apt-get -y install  mc
ADD ./docker/php/local.ini usr/local/etc/php/conf.d/local.ini
WORKDIR /app
EXPOSE 9000
ENTRYPOINT php-fpm
