#!/bin/bash
set -e

# Give permissions to storage and cache
chmod -R 775 storage bootstrap/cache

echo "Waiting for database..."

until php artisan migrate --force; do
  echo "Database not ready, retrying in 3s..."
  sleep 3
done

# Seed AFTER successful migration
php artisan db:seed --force

# Cache configs
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP server on Railway PORT
php -S 0.0.0.0:${PORT} -t public
