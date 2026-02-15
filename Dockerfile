# Use the official PHP image with required extensions
FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    npm \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .


# Install PHP and JS dependencies and build assets
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build && \
    test -f public/build/manifest.json || (echo 'Vite manifest missing!' && exit 1)

# Expose port
EXPOSE 10000

# Start Laravel server and clear caches
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan route:clear && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=10000
