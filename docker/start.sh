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

# Start queue worker in background
php artisan queue:work --daemon --tries=3 --timeout=90 &

# Start PHP-FPM
php-fpm -D

# Start Nginx AND tail logs simultaneously
nginx -g 'daemon off;' &
tail -f /var/www/html/storage/logs/laravel.log &
wait
```

### Step 4: Add Mail Environment Variables to Render

Go to your Render dashboard → Your service → Environment → Add these:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=iamgiancarlli@gmail.com
MAIL_PASSWORD=ricaeycqebridruc
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=iamgiancarlli@gmail.com
MAIL_FROM_NAME=Paige Invitation
QUEUE_CONNECTION=database