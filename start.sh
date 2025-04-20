#!/bin/sh

# Inicia PHP-FPM
php-fpm --daemonize

# Inicia Nginx em foreground
nginx -g "daemon off;"
