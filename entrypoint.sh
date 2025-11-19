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

# Set permission untuk storage/framework (untuk compiled views)
chmod -R 775 /var/www/html/storage/framework || true
chmod -R 775 /var/www/html/storage/logs || true
chmod -R 775 /var/www/html/bootstrap/cache || true

# Pastikan semua folder di storage writable
find /var/www/html/storage -type d -exec chmod 775 {} \; || true
find /var/www/html/storage -type f -exec chmod 664 {} \; || true

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
# Set permission secara recursive untuk semua file dan folder
# Hanya gunakan chmod, jangan chown (karena mungkin user www-data tidak ada)
chmod -R 755 /var/www/html/storage/app/public || true

# Pastikan semua file readable (644) dan semua folder executable (755)
find /var/www/html/storage/app/public -type f -exec chmod 644 {} \; || true
find /var/www/html/storage/app/public -type d -exec chmod 755 {} \; || true

# Pastikan parent directories juga readable
chmod 755 /var/www/html/storage/app/public/gallery 2>/dev/null || true

# Set permission untuk semua parent directories juga
chmod 755 /var/www/html/storage || true
chmod 755 /var/www/html/storage/app || true

# Debug: list beberapa file untuk verifikasi
echo "=== Storage files check ==="
ls -la /var/www/html/storage/app/public/ | head -10 || true
if [ -d /var/www/html/storage/app/public/gallery ]; then
    echo "=== Gallery folder check ==="
    ls -la /var/www/html/storage/app/public/gallery/ | head -10 || true
    echo "=== Gallery folder permissions ==="
    stat -c "%a %n" /var/www/html/storage/app/public/gallery/* 2>/dev/null | head -5 || true
fi

# Pastikan public directory juga bisa diakses
chmod 755 /var/www/html/public || true

# Jalankan Apache
exec apache2-foreground

