#!/bin/bash

# Optimize Laravel caches for production performance
php artisan config:clear
php artisan route:cache
php artisan view:cache

# Run migrations and ensure storage symlink exists
php artisan migrate --force
php artisan storage:link || true

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground
nginx -g "daemon off;"
