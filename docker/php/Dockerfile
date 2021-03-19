FROM php:7.4-fpm

ARG PHP_ROOT_DIR

COPY php.ini /usr/local/etc/php/php.ini
RUN apt-get update \
    && apt-get install -y libpng-dev unzip libicu-dev libzip-dev \
    && docker-php-ext-install pdo_mysql bcmath gd zip intl \
    && pecl install xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN apt autoremove -y && apt-get clean && apt-get purge
