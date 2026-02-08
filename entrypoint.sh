#!/bin/bash
set -e

chmod -R 775 storage bootstrap/cache

echo "Waiting for database..."

until php artisan migrate:status; do
    echo "Waiting for database..."
    sleep 2
done
php artisan migrate --seed --force


php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

php -S 0.0.0.0:${PORT} -t public
