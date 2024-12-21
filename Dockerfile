ARG PHP_VERSION=8.4.1
ARG PYTHON_VERSION=3.13.0
ARG COMPOSER_VERSION=2.8.3
ARG DEV_ENV=false  # Adicionando a variável para controle do ambiente

# Estágio para composer
FROM composer:$COMPOSER_VERSION AS php-composer
RUN /usr/bin/composer -v

# Estágio para PHP
FROM php:$PHP_VERSION-cli-alpine AS php

ENV CFLAGS="-D_GNU_SOURCE"

# Instalar dependências necessárias
RUN apk add --no-cache \
    bzip2-dev \
    libsodium-dev \
    libxml2-dev \
    libxslt-dev \
    linux-headers \
    yaml-dev \
    sqlite-dev \
    autoconf \
    gcc \
    make \
    g++ \
    zlib-dev \
    libxml2-dev

# Copiar os arquivos php.ini para dentro da imagem
COPY docker/php.ini-development /docker/php.ini-development
COPY docker/php.ini-production /docker/php.ini-production

# Copiar o arquivo php.ini correto com base no ambiente
RUN if [ "$DEV_ENV" = "true" ]; then cp /docker/php.ini-development "$PHP_INI_DIR/php.ini"; \
    else cp /docker/php.ini-production "$PHP_INI_DIR/php.ini"; fi

# Database Config
RUN mkdir -p /var/www/html/database/ \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/database/ \
    && chmod -R 775 /var/www/html/database/

# Instalar extensões PHP
RUN docker-php-ext-install \
    bz2 \
    calendar \
    exif \
    opcache \
    pcntl \
    shmop \
    soap \
    sockets \
    sodium \
    sysvsem \
    sysvshm \
    xsl \
    pdo_sqlite  # Instalar PDO_SQLite para SQLite via PDO

# Instalar a extensão YAML via PECL
RUN pecl install yaml && \
    docker-php-ext-enable yaml

# Instalar o Xdebug somente em ambiente de desenvolvimento
RUN if [ "$DEV_ENV" = "true" ]; then \
    pecl install xdebug && \
    docker-php-ext-enable xdebug; \
fi

# Limpar as dependências de compilação
RUN apk del --purge autoconf gcc make g++ zlib-dev

# Instalar o GNU iconv no PHP
RUN apk add gnu-libiconv
ENV LD_PRELOAD="/usr/lib/preloadable_libiconv.so php-fpm php"
RUN php -r '$res = iconv("utf-8", "utf-8//IGNORE", "fooą");'

# Verificar PHP e extensões
RUN which php; php -v; php -m; php -i | grep ini

# Definir o comando padrão de execução do contêiner
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html/"]

# Estágio para Python
FROM python:$PYTHON_VERSION-alpine
RUN apk add --no-cache bash
ARG PHP_VERSION
ARG COMPOSER_VERSION
ARG DEV_ENV

# Instalar o virtualenv para Python
RUN pip install virtualenv && rm -rf /root/.cache
RUN python -V

# Copiar o composer da primeira etapa
COPY --from=php-composer /usr/bin/composer /usr/bin

# Copiar o binário do PHP e as libs necessárias
COPY --from=php /usr/local/bin/php /usr/bin
COPY --from=php /usr/local/etc/php /usr/local/etc/php
COPY --from=php /usr/lib/*.so.* /usr/lib/
COPY --from=php /usr/local/lib/php /usr/local/lib/php

# Configurações de permissões e banco de dados
RUN mkdir -p /var/www/html/database/ \
    && adduser -u 82 -D -S -G www-data www-data \
    && mkdir -p /var/www/html/database/ \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/database/ \
    && chmod -R 775 /var/www/html/database/

# Add info script
WORKDIR /opt

RUN echo "echo -e '### Python'; python -V; virtualenv --version; echo -e '\n### PHP'; php -v; composer -V; php -m" > info.sh
RUN chmod 744 info.sh


# docker build --build-arg DEV_ENV=true -t checkprice .