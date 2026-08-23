#!/bin/bash

# Clear config cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground
nginx -g "daemon off;"
