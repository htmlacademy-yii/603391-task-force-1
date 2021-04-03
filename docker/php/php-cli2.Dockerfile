FROM php:8.0.3-cli
RUN apt-get update
RUN docker-php-ext-install pdo_mysql
CMD sleep 15
WORKDIR /app
CMD php ./init --env=Docker --overwrite=All
CMD  ./docker/wait-for-it.sh mysql:3306 -- php yii migrate --interactive=0