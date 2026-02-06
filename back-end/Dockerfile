FROM php:8.2-fpm

# 1. Install system dependencies (Required for Composer and common PHP extensions)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# 2. Install PHP extensions (Added mbstring, bcmath, gd etc which are standard for Laravel)
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# 3. Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set Working Directory
WORKDIR /var/www/html

# 5. COPY SOURCE CODE (This was missing!)
# Copies everything from Module_Tuyendung/back-end to /var/www/html
COPY . .

# 6. Install PHP Dependencies
# --no-dev: Don't install testing packages
# --optimize-autoloader: Speed up loading
RUN composer install --no-dev --optimize-autoloader

# 7. Set Permissions
# Ensure the web server (www-data) can write to storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
