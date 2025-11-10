# Gunakan PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copy semua file project ke container
COPY . /var/www/html

# Atur working directory
WORKDIR /var/www/html

# Install Composer
RUN apt-get update && apt-get install -y unzip git curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Set permission biar Laravel bisa tulis log/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Jalankan Apache
EXPOSE 80
CMD ["apache2-foreground"]
