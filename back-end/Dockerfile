FROM php:8.2-fpm

# Cài extension cần thiết cho Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
