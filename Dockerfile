ARG PHP_VERSION=8.3.1
ARG COMPOSER_VERSION=2.8.3

# Base PHP
FROM php:$PHP_VERSION-cli-alpine

# Instalar dependências do PHP e Composer
RUN apk add --no-cache \
 bash \
 bzip2-dev \
 libsodium-dev \
 libxml2-dev \
 libxslt-dev \
 linux-headers \
 yaml-dev \
 sqlite-dev \
 gcc \
 make \
 g++ \
 zlib-dev \
 autoconf \
 nginx \
 php83-dev \
 php83-xml \
 php83-phar \
 openssl \
 expect \
 oniguruma-dev \
 gnu-libiconv \
 curl \
 --repository=http://dl-cdn.alpinelinux.org/alpine/edge/testing

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Configurar iconv
ENV LD_PRELOAD=/usr/lib/libiconv/preloadable_libiconv.so
ENV PHP_OPENSSL_DIR=/usr
ENV CFLAGS="-I/usr/include/openssl/"
ENV LDFLAGS="-L/usr/lib/"

# Instalar extensões PHP
RUN docker-php-ext-install \
 bz2 calendar exif opcache pcntl shmop soap sockets sodium sysvsem sysvshm xsl pdo_sqlite && \
 pecl install yaml && docker-php-ext-enable yaml && \
 docker-php-ext-install mbstring

WORKDIR /var/www/html

# Copiar código-fonte
COPY . /var/www/html

# Permissões
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html && \
 chmod +x /var/www/html/scripts/setup-php.sh && chmod +x /var/www/html/start.sh

# Copiar nginx config
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["/var/www/html/start.sh"]
