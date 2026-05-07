FROM php:8.4-fpm-alpine AS base
RUN apk add --no-cache postgresql-dev $PHPIZE_DEPS \
 && docker-php-ext-install pdo pdo_pgsql opcache \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && apk del $PHPIZE_DEPS
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

FROM base AS dev
WORKDIR /var/www
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --optimize --no-scripts

FROM base AS production
WORKDIR /var/www
COPY . .
RUN composer install --no-dev --optimize-autoloader
RUN adduser -D -H -u 1001 appuser && chown -R appuser /var/www
USER appuser
