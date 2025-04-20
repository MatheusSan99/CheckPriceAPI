#!/bin/bash

set -e  # Para falhar em caso de erros
set -x  # Para debug

CONTAINER_PHP_DIR="/var/www/html"
SCRIPTS_DIR="/var/www/html/scripts"
PHP_INI_DIR="/var/www/html/docker"

if [ -d "vendor" ]; then
  echo ">> Apagando dependências existentes em vendor/..."
  rm -rf vendor/*
fi

# Instalar as dependências PHP
echo ">> Instalando dependências PHP..."
composer install --no-interaction --no-dev --optimize-autoloader
echo ">> Dependências PHP instaladas com sucesso!"
composer dump-autoload --optimize


if [ "$DEV_ENV" = "true" ]; then
    echo "Ambiente de desenvolvimento detectado..."

    if php -m | grep -q xdebug; then
        echo "Xdebug já está instalado"
        exit 0
    fi

    cd "$CONTAINER_PHP_DIR"

    # Verificar se o curl está instalado e, se necessário, instalá-lo
    if ! apk info --installed curl >/dev/null 2>&1; then
        echo "Instalando curl..."
        apk add --no-cache curl
    fi

    #Compilador de C
    if ! apk info --installed gcc >/dev/null 2>&1; then
        echo "Instalando gcc..."
        apk add --no-cache gcc || { echo "Erro ao instalar o gcc"; exit 0; }
    fi

    #Libc e headers
    if ! apk info --installed libc-dev >/dev/null 2>&1; then
        echo "Instalando libc-dev..."
        apk add --no-cache libc-dev || { echo "Erro ao instalar o libc-dev"; exit 0; }
    fi

    #Linux Headers
    if ! apk info --installed linux-headers >/dev/null 2>&1; then
        echo "Instalando linux-headers..."
        apk add --update linux-headers || { echo "Erro ao instalar o linux-headers"; exit 0; }
    fi

    #Make
    if ! apk info --installed make >/dev/null 2>&1; then
        echo "Instalando make..."
        apk add --no-cache make || { echo "Erro ao instalar o make"; exit 0; }
    fi
    # Instalar expect
    if ! apk info --installed expect >/dev/null 2>&1; then
        echo "Instalando expect..."
        apk add --no-cache expect || { echo "Erro ao instalar o expect"; exit 0; }
    fi

    # Executar o script de instalação do go-pear
    if [ -x "$SCRIPTS_DIR/install-gopear.sh" ]; then
        "$SCRIPTS_DIR/install-gopear.sh" || { echo "Erro ao instalar o PEAR"; exit 0; }
    else
        echo "Erro: Script install-gopear.sh não encontrado ou sem permissão de execução"
        exit 0
    fi

    # Verificar a instalação do PECL
    if ! command -v pecl &>/dev/null; then
        echo "Erro: PECL não está instalado"
        exit 0
    fi

    # Instalar o Xdebug via PECL
    echo "Instalando Xdebug..."
    pecl install xdebug || { echo "Erro ao instalar o Xdebug"; exit 0; }

    # Habilitar o Xdebug
    if command -v docker-php-ext-enable &>/dev/null; then
        docker-php-ext-enable xdebug || { echo "Erro ao habilitar o Xdebug"; exit 0; }
        
        # Copiar as configurações para o arquivo de configuração do Xdebug
        echo "Configurando Xdebug..."
        echo "xdebug.mode=debug" > /var/www/html/docker/conf.d/docker-php-ext-xdebug.ini
        echo "xdebug.start_with_request=yes" >> /var/www/html/docker/conf.d/docker-php-ext-xdebug.ini
        echo "xdebug.client_port=9003" >> /var/www/html/docker/conf.d/docker-php-ext-xdebug.ini
        echo "xdebug.client_host=172.17.0.1" >> /var/www/html/docker/conf.d/docker-php-ext-xdebug.ini
        echo "xdebug.log=/tmp/xdebug_remote.log" >> /var/www/html/docker/conf.d/docker-php-ext-xdebug.ini
    else
        echo "Aviso: docker-php-ext-enable não encontrado. Verifique a configuração manual do Xdebug."
    fi


    # Remover o arquivo go-pear.phar, se existir
    if [ -f go-pear.phar ]; then
        rm -f go-pear.phar
    fi

    cp "$PHP_INI_DIR/php.ini-development" "../../../etc/php83/php.ini" || { echo "Erro ao copiar o php.ini"; exit 0; }
else
    echo "Ambiente de produção detectado..."
    cp "$PHP_INI_DIR/php.ini-production" "../../../etc/php83/php.ini" || { echo "Erro ao copiar o php.ini"; exit 0; }
fi

#Instalar session se nao tiver instalado
if ! php -m | grep -q session; then
    echo "Instalando session..."
    apk add --no-cache php83-session || { echo "Erro ao instalar o session"; exit 0; }
fi

#Instalar Pdo se nao tiver instalado
if ! php -m | grep -q pdo; then
    echo "Instalando pdo..."
    apk add --no-cache php83-pdo || { echo "Erro ao instalar o pdo"; exit 0; }
fi

#Instalar Nginx se nao tiver instalado
if ! apk info --installed nginx >/dev/null 2>&1; then
    echo "Instalando nginx..."
    apk add --no-cache nginx || { echo "Erro ao instalar o nginx"; exit 0; }
fi

#Instalar phpfmp se nao tiver instalado
if ! apk info --installed php83-fpm >/dev/null 2>&1; then
    echo "Instalando php83-fpm..."
    apk add --no-cache php83-fpm || { echo "Erro ao instalar o php83-fpm"; exit 0; }
fi

#Deletar Dependencias desnescessarias que sao utilizadas apenas na compilacao
if apk info --installed gcc >/dev/null 2>&1; then
    apk del gcc make g++ linux-headers autoconf
fi
