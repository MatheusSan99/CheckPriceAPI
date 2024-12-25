# Definindo versões
ARG PHP_VERSION=8.3.1
ARG PYTHON_VERSION=3.13.0
ARG COMPOSER_VERSION=2.8.3

# Estágio para composer
FROM composer:$COMPOSER_VERSION AS php-composer
RUN /usr/bin/composer -v

# Estágio para PHP
FROM php:$PHP_VERSION-cli-alpine AS php

ENV CFLAGS="-D_GNU_SOURCE"
# Instalar dependências necessárias e extensões PHP
RUN apk add --no-cache \
    bash \
    bzip2-dev libsodium-dev libxml2-dev libxslt-dev \
    linux-headers yaml-dev sqlite-dev \
    gcc make g++ zlib-dev autoconf nginx \
    php83-dev php83-xml openssl expect && \
    docker-php-ext-install \
    bz2 calendar exif opcache pcntl shmop soap \
    sockets sodium sysvsem sysvshm xsl pdo_sqlite && \
    pecl install yaml && docker-php-ext-enable yaml

# Instalar o GNU iconv no PHP
RUN apk add gnu-libiconv
ENV LD_PRELOAD="/usr/lib/preloadable_libiconv.so php-fpm php"
RUN php -r '$res = iconv("utf-8", "utf-8//IGNORE", "fooą");'

# Copiar os arquivos do diretório atual para o container
# Garante a permissao localmente nos scripts
RUN chmod ./scripts/setup-php.sh
RUN chmod ./start.sh
COPY . /var/www/html

# Garantir permissões corretas
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html

# Permissao scripts    
RUN chmod +x /var/www/html/scripts/setup-php.sh
RUN chmod +x /var/www/html/start.sh

# Estágio para Python
FROM python:$PYTHON_VERSION-alpine
RUN apk add --no-cache bash

# Instalar o virtualenv para Python
RUN pip install virtualenv && rm -rf /root/.cache

# Copiar o composer da primeira etapa
COPY --from=php-composer /usr/bin/composer /usr/bin

# Copiar o binário do PHP e as libs necessárias
COPY --from=php /usr/local/bin/php /usr/bin
COPY --from=php /usr/local/etc/php /usr/local/etc/php
COPY --from=php /usr/lib/*.so.* /usr/lib/
COPY --from=php /usr/local/lib/php /usr/local/lib/php

# Copiar a configuração do Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# Expor a porta 80 para o Nginx
EXPOSE 80

# Definir o diretório de trabalho
WORKDIR /var/www/html

CMD ["/var/www/html/start.sh"]
