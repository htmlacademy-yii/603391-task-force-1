FROM php:8.0.3-cli
RUN apt-get update
RUN docker-php-ext-install pdo_mysql
RUN apt-get -y install  expect
WORKDIR /app
EXPOSE 9000
#CMD php-fpm
#CMD php ./init --env=Docker --overwrite=All
#CMD expect -c 'spawn php ./yii migrate; expect "Apply the above migrations" {send -- "yes\r"}; interact'
#
