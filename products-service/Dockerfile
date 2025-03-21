FROM php:8.3.3-fpm-bookworm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    vim \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www

COPY . /var/www

RUN chown -R www-data:www-data \
    /var/www \
    /var/www/storage \
    /var/www/bootstrap/cache

RUN chmod -R 775 /var/www/storage

EXPOSE 9000
