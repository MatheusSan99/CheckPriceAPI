FROM php:8.3.1-fpm-alpine as builder

ARG DEV_ENV=false

RUN apk add --no-cache gcc g++ make autoconf linux-headers nginx

RUN if [ "$DEV_ENV" = "true" ]; then \
        pecl install xdebug && \
        docker-php-ext-enable xdebug; \
    fi

FROM php:8.3.1-fpm-alpine as final

ARG DEV_ENV=false

RUN apk add --no-cache nginx

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY . /var/www/html/

WORKDIR /var/www/html

RUN if [ "$DEV_ENV" = "true" ]; then \
        cp /var/www/html/docker/php.ini-development /usr/local/etc/php/php.ini; \
    else \
        cp /var/www/html/docker/php.ini-production /usr/local/etc/php/php.ini; \
    fi

COPY ./nginx/default.conf /etc/nginx/http.d/default.conf

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
