#!/bin/bash
set -e

chmod -R 775 storage bootstrap/cache

echo "Waiting for database..."

until php artisan migrate --force; do
  echo "Database not ready, retrying in 3s..."
  sleep 3
done

# ✅ Seed ONCE only
php artisan db:seed --force || true

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

php -S 0.0.0.0:${PORT} -t public
