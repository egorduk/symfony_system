FROM php:7.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    libzip-dev \
    zip \
    && docker-php-ext-install mysqli \
    #&& pecl install xdebug && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \