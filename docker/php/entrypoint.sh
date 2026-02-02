#!/bin/bash
set -e

# Ensure Laravel storage and cache directories exist and are writable (for volume mounts)
cd /var/www/html
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
