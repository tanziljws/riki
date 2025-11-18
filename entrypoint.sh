#!/bin/bash
set -e

# Set PORT dari environment variable (default 80 untuk local development)
PORT=${PORT:-80}

# Update Apache Listen directive
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf

# Update VirtualHost untuk listen di PORT yang benar
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/*.conf

# Set permission untuk storage (sangat penting!)
chmod -R 755 /var/www/html/storage/app/public || true
chown -R www-data:www-data /var/www/html/storage/app/public || true

# Pastikan file bisa dibaca oleh web server
find /var/www/html/storage/app/public -type f -exec chmod 644 {} \; || true
find /var/www/html/storage/app/public -type d -exec chmod 755 {} \; || true

# BUAT SYMLINK - ini cara standar Laravel untuk serve storage files
cd /var/www/html
php artisan storage:link || true

# Set permission untuk symlink juga
if [ -L /var/www/html/public/storage ]; then
    chown -h www-data:www-data /var/www/html/public/storage || true
    chmod 777 /var/www/html/public/storage || true
fi

# Jalankan Apache
exec apache2-foreground

