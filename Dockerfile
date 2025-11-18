# Gunakan PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Enable mod_rewrite untuk Laravel
RUN a2enmod rewrite

# Set ServerName untuk menghilangkan warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Atur DocumentRoot ke public folder (Laravel standard)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy semua file project ke container
COPY . /var/www/html

# Atur working directory
WORKDIR /var/www/html

# Install Composer
RUN apt-get update && apt-get install -y unzip git curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/*

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Buat symbolic link untuk storage (Laravel requirement)
RUN php artisan storage:link || true

# Set permission biar Laravel bisa tulis log/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy dan set entrypoint script untuk handle PORT dari Railway
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Jalankan Apache via entrypoint
EXPOSE 80
CMD ["/usr/local/bin/entrypoint.sh"]
