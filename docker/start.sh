#!/bin/bash

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear caches (remove cache:clear since cache table doesn't exist yet)
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Start PHP-FPM
php-fpm -D

# Start Nginx AND tail logs simultaneously
nginx -g 'daemon off;' &
tail -f /var/www/html/storage/logs/laravel.log &
wait