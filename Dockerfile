# Dockerfile
FROM php:8.2-apache

# Instala la extensión mysqli
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli
