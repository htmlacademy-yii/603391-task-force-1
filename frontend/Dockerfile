FROM php:8.0.2-apache

# Change document root for Apache
RUN sed -i -e 's|/var/www/html|/app/frontend/web|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i -e 's|<Directory /var/www/>|<Directory /app/frontend/web>|g' /etc/apache2/apache2.conf
RUN sed -i -e 's|AllowOverride none|AllowOverride all|g' /etc/apache2/apache2.conf
RUN ls /usr/local/etc/php/
RUN sed -i -e 's|;extension=pdo_mysql|extension=pdo_mysql|g' /usr/local/etc/php/php.ini-development
RUN sed -i -e 's|;extension=pdo_mysql|extension=pdo_mysql|g' /usr/local/etc/php/php.ini-production
RUN apt update
RUN apt -y install mc
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
