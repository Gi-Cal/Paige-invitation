#!/bin/bash

# Fix permissions for storage and cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Start PHP-FPM and Nginx
php-fpm -D
nginx -g 'daemon off;'
```

### Issue 2: Use PostgreSQL (Not MySQL)

Your Render environment variables are still set to `DB_CONNECTION=mysql`, but your Docker image doesn't have MySQL drivers.

**In Render Environment Variables, change:**
```
DB_CONNECTION=pgsql
```

**And make sure you have:**
```
DATABASE_URL=<your PostgreSQL Internal Database URL>