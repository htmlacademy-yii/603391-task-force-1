FROM php:8.0.3-fpm
RUN apt-get update
RUN apt-get -y install  mc
RUN apt-get -y install  expect
#CMD php ./init --env=Docker --overwrite=All
#CMD expect -c 'set timeout 5; spawn php ./yii migrate; expect "Apply the above migrations" {send -- "yes\r"}; interact'
EXPOSE 9000
ADD ./docker/php/local.ini usr/local/etc/php/conf.d/local.ini
WORKDIR /app
EXPOSE 9000
ENTRYPOINT php-fpm


