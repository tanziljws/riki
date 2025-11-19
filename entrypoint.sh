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

# Pastikan semua parent directory juga readable
chmod 755 /var/www/html/storage || true
chmod 755 /var/www/html/storage/app || true
chown -R www-data:www-data /var/www/html/storage || true

# BUAT SYMLINK - ini cara standar Laravel untuk serve storage files
cd /var/www/html

# Hapus symlink lama jika ada
rm -f /var/www/html/public/storage || true

# Buat symlink baru dengan absolute path
ln -sf /var/www/html/storage/app/public /var/www/html/public/storage || true

# Set permission untuk symlink
if [ -L /var/www/html/public/storage ]; then
    chown -h www-data:www-data /var/www/html/public/storage || true
    chmod 777 /var/www/html/public/storage || true
    echo "SUCCESS: storage symlink created"
    ls -la /var/www/html/public/storage
else
    echo "ERROR: Failed to create storage symlink"
    # Fallback: buat dengan artisan
    php artisan storage:link || true
fi

# Final permission check
chmod -R 755 /var/www/html/storage/app/public || true
chown -R www-data:www-data /var/www/html/storage/app/public || true
find /var/www/html/storage/app/public -type f -exec chmod 644 {} \; || true

# Jalankan Apache
exec apache2-foreground

