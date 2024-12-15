FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libsqlite3-dev \
    zip \
    poppler-utils

RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    zip

ADD docker/php-custom.ini "$PHP_INI_DIR/conf.d/custom.ini"
ADD docker/apache2.conf "$APACHE_CONFDIR/conf-enabled/apache2.conf"

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY . /var/www/html

RUN mkdir -p /var/www/html/database/ \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/database/ \
    && chmod -R 775 /var/www/html/database/

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

# docker build --build-arg INSTALL_XDEBUG=false -t minha-imagem:prod . PROD
# docker build --build-arg INSTALL_XDEBUG=true -t minha-imagem:dev . DEV

