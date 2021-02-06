FROM php:8.0.1-apache

# Change document root for Apache
RUN sed -i -e 's|/var/www/html|/app/frontend/web|g' /etc/apache2/sites-available/000-default.conf
