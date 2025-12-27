#!/bin/bash

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground AND tail Laravel logs
nginx -g 'daemon off;' &

# Wait a moment for services to start
sleep 2

# Tail Laravel logs (this will show errors in Render logs)
tail -f /var/www/html/storage/logs/laravel.log