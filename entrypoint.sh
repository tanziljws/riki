#!/bin/bash
set -e

# Set PORT dari environment variable (default 80 untuk local development)
PORT=${PORT:-80}

# Update Apache Listen directive
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf

# Update VirtualHost untuk listen di PORT yang benar
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/*.conf

# Pastikan storage symlink ada (kadang tidak terbuat saat build)
cd /var/www/html
php artisan storage:link || true

# Set permission untuk storage
chmod -R 755 /var/www/html/storage/app/public || true

# Jalankan Apache
exec apache2-foreground

