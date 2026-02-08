#!/bin/bash
set -e

# Give permissions to storage and cache
chmod -R 775 storage bootstrap/cache

# Wait for DB to be ready before migrating
until php artisan migrate --force
php artisan db:seed --force
; do
    echo "Waiting for database..."
    sleep 3
done

# Cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP built-in server on Railway's PORT
php -S 0.0.0.0:$PORT -t public
