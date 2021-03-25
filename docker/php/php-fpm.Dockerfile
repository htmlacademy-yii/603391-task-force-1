FROM php:8.0.3-fpm
RUN docker-php-ext-install pdo_mysql
RUN apt-get update
RUN apt-get -y install  mc
RUN apt-get -y install  expect
ADD ./docker/php/local.ini usr/local/etc/php/conf.d/local.ini
WORKDIR /app
EXPOSE 9000
RUN php -v
CMD php ./init --env=Docker --overwrite=All
CMD expect -c 'set timeout 5; spawn php ./yii migrate; expect "Apply the above migrations" {send -- "yes\r"}; interact'
ENTRYPOINT php-fpm
