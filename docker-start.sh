#!/bin/sh
set -e

cd /var/www/html

# Render tells us which port to listen on
PORT="${PORT:-10000}"
sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Free Render has an empty disk on every start, so rebuild the database each time
mkdir -p database storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
touch database/database.sqlite
php artisan migrate --seed --force
php artisan storage:link --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage bootstrap/cache database

exec apache2-foreground
