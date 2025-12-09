# Gunakan image PHP 8.2 resmi dengan Apache
FROM php:8.2-apache

# Install dependencies sistem yang diperlukan
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Aktifkan mod_rewrite Apache untuk URL Laravel
RUN a2enmod rewrite

# Set direktori kerja
WORKDIR /var/www/html

# Copy file composer
COPY composer.json composer.lock ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependensi PHP (tanpa dev dependencies untuk production)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy seluruh source code proyek
COPY . .

# Set permission folder storage dan cache agar bisa ditulis
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ubah document root Apache ke folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Expose port 80 (Railway akan mapping port ini otomatis)
EXPOSE 80

# Jalankan Apache saat container start
# CMD menjalankan migrasi dan cache config setiap kali deploy
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    apache2-foreground
