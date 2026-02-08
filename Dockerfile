# Stage 1: Build stage
FROM php:8.2-fpm AS build

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev curl npm nodejs \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Fix permissions
RUN chmod -R 775 storage bootstrap/cache

# Stage 2: Production stage with PHP-FPM + Nginx
FROM nginx:alpine

# Copy built Laravel app from previous stage
COPY --from=build /var/www/html /var/www/html

# Copy Nginx config
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port
EXPOSE 8080

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
