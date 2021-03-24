FROM nginx:stable
RUN apt-get update
RUN apt-get -y install mc
ADD ./docker/nginx/conf.d/default.nginx /etc/nginx/conf.d/default.conf
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
CMD php composer.phar install
CMD php yii migrate
ENTRYPOINT ["nginx", "-g", "daemon off;"]
