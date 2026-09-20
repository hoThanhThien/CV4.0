#!/bin/bash

# Optimize Laravel caches for production performance
php artisan config:clear
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground
nginx -g "daemon off;"
