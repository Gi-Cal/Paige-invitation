#!/bin/bash

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Start PHP-FPM
php-fpm -D

# Start queue worker with auto-restart
while true; do
    php artisan queue:work --sleep=3 --tries=3 --max-time=3600 2>&1
    echo "Queue worker stopped, restarting in 5 seconds..."
    sleep 5
done &

# Tail Laravel logs to see them in Render console
tail -f /var/www/html/storage/logs/laravel.log &

# Start Nginx in foreground
nginx -g 'daemon off;'