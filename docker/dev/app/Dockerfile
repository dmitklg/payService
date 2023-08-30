FROM php:8.1.10-fpm-alpine

RUN apk update && apk add --no-cache \
     autoconf \
     g++ \
     make \
     postgresql-dev \
     icu-dev \
     bash
RUN docker-php-ext-install \
         pdo_pgsql \
         intl \
         sockets \
     && apk del \
       postgresql-libs \
       libsasl \
       db \
     && rm -rf /tmp/* /var/cache/apk/*
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY ./config/*.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/www
