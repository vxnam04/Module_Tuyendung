FROM php:8.2-fpm

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# 2. Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# 3. Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set Working Directory
WORKDIR /var/www/html

# 5. COPY SOURCE CODE
COPY . .

# 6. Install PHP Dependencies
RUN composer install --no-dev --optimize-autoloader

# 7. Create missing directories (THE FIX)
# We use -p to ensure parent directories are created and no error if they exist
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache

# 8. Set Permissions
RUN chown -R www-data:www-data storage bootstrap/cache
