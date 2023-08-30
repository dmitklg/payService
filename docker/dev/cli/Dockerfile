FROM php:8.1.10-cli-alpine

RUN apk update && apk add --no-cache \
    autoconf \
    g++ \
    make \
    postgresql-dev \
    icu-dev \
    git \
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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
  && rm -rf /root/.composer/cache

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app
WORKDIR /var/www
