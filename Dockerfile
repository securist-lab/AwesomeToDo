FROM php:8.1-fpm

WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html
