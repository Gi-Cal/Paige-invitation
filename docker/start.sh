#!/bin/bash

# Your existing startup commands...
php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Start services
php-fpm &
nginx -g 'daemon off;' &

# Tail Laravel logs to Render's log viewer (FREE)
tail -f /var/www/html/storage/logs/laravel.log