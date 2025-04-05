FROM composer:2.5.8 AS vendor

WORKDIR /var/www

COPY composer.json /var/www/composer.json

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist



FROM php:8.3.3-fpm-bookworm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    vim \
    libzip-dev \
    zip \
    net-tools \
    iputils-ping \
    iproute2 \
    unzip \
    && docker-php-ext-install pdo pdo_mysql


COPY . /var/www

COPY --from=vendor /usr/bin/composer /usr/bin/composer


RUN chown -R www-data:www-data \
    /var/www \
    /var/www/storage \
    /var/www/bootstrap/cache

RUN chmod -R 775 /var/www/storage

EXPOSE 9000

#MANUAL COMMANDS
#chown -R www-data:www-data /var/www/storage
#chown -R www-data:www-data /var/www/bootstrap/cache
