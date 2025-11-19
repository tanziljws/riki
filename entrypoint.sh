#!/bin/bash
set -e

# Set PORT dari environment variable (default 80 untuk local development)
PORT=${PORT:-80}

# Update Apache Listen directive
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf

# Update VirtualHost untuk listen di PORT yang benar
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/*.conf

# Pastikan VirtualHost memiliki Options +FollowSymLinks +Indexes
# Ini penting untuk Apache bisa akses symlink dan file
for conf in /etc/apache2/sites-available/*.conf; do
    if ! grep -q "Options +FollowSymLinks" "$conf"; then
        sed -i '/<Directory \/var\/www\/html\/public>/a\    Options +FollowSymLinks +Indexes' "$conf" || true
    fi
done

# Set permission untuk storage (sangat penting!)
# Pastikan semua parent directory readable
chmod 755 /var/www/html || true
chmod 755 /var/www/html/storage || true
chmod 755 /var/www/html/storage/app || true
chmod -R 755 /var/www/html/storage/app/public || true

# Set ownership
chown -R www-data:www-data /var/www/html/storage || true
chown -R www-data:www-data /var/www/html/storage/app/public || true

# Pastikan file bisa dibaca oleh web server (644 = readable by all)
find /var/www/html/storage/app/public -type f -exec chmod 644 {} \; || true
find /var/www/html/storage/app/public -type d -exec chmod 755 {} \; || true

# BUAT SYMLINK - ini cara standar Laravel untuk serve storage files
cd /var/www/html

# Hapus symlink lama jika ada
rm -f /var/www/html/public/storage || true

# Buat symlink baru dengan absolute path
ln -sfn /var/www/html/storage/app/public /var/www/html/public/storage || true

# Set permission untuk symlink dan pastikan bisa diakses
if [ -L /var/www/html/public/storage ]; then
    chown -h www-data:www-data /var/www/html/public/storage || true
    chmod 755 /var/www/html/public/storage || true
    echo "SUCCESS: storage symlink created"
    ls -la /var/www/html/public/ | grep storage
    # Test apakah symlink bisa diakses
    ls -la /var/www/html/public/storage/ | head -5 || echo "WARNING: Cannot list symlink contents"
else
    echo "ERROR: Failed to create storage symlink"
    # Fallback: buat dengan artisan
    php artisan storage:link || true
    if [ -L /var/www/html/public/storage ]; then
        echo "SUCCESS: storage symlink created via artisan"
    fi
fi

# Final permission check - pastikan semua bisa dibaca
chmod -R 755 /var/www/html/storage/app/public || true
chown -R www-data:www-data /var/www/html/storage/app/public || true
find /var/www/html/storage/app/public -type f -exec chmod 644 {} \; || true
find /var/www/html/storage/app/public -type d -exec chmod 755 {} \; || true

# Pastikan public directory juga bisa diakses
chmod 755 /var/www/html/public || true
chown www-data:www-data /var/www/html/public || true

# Jalankan Apache
exec apache2-foreground

