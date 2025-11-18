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

# Jangan buat symlink - biarkan Laravel route yang handle
# php artisan storage:link || true

# Jalankan Apache
exec apache2-foreground

