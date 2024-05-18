FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite

ADD docker/centos.conf/xdebug.ini "$PHP_INI_DIR/conf.d/xdebug.ini"

RUN pecl install xdebug

ADD ./php.ini-development "$PHP_INI_DIR/php.ini"
ADD ./apache2.conf "$APACHE_CONFDIR/conf-enabled/apache2.conf"

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

COPY . /var/www/html

RUN mkdir -p /var/www/html/database/ \
    && touch /var/www/html/database/database.sqlite \
    && chown www-data:www-data /var/www/html/database/database.sqlite \
    && chmod 664 /var/www/html/database/database.sqlite

WORKDIR /var/www/html
