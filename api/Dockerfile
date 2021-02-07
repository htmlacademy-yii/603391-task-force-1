FROM php:8.0.2-apache

# Change document root for Apache
RUN sed -i -e 's|/var/www/html|/app/api/web|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i -e 's|<Directory /var/www/>|<Directory /app/api/web>|g' /etc/apache2/apache2.conf
RUN sed -i -e 's|AllowOverride none|AllowOverride all|g' /etc/apache2/apache2.conf