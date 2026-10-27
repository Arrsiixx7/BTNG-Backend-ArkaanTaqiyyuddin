FROM php:8.4-cli-alpine

# Set environment
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PORT=10000

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    mariadb-connector-c-dev \
    && docker-php-ext-install pdo pdo_mysql pcntl bcmath

# Get Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=php+

# Ensure storage directories have write permissions
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000

# Entrypoint to boot Laravel server on Render-assigned PORT
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
