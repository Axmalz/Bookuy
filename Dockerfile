# Gunakan image PHP 8.2 resmi dengan Apache
FROM php:8.2-apache

# Install dependencies sistem
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

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Composer files
COPY composer.json composer.lock ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache Document Root to public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Allow .htaccess override
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# --- BAGIAN PENTING: KONFIGURASI PORT DINAMIS ---
# Ubah port default Apache (80) menjadi port yang diberikan Railway ($PORT) saat runtime
# Jika $PORT tidak ada, default ke 8000
RUN sed -s -i -e "s/80/\${PORT:-8000}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Expose port (hanya dokumentasi, Railway akan override ini)
EXPOSE 8000

# Jalankan script startup
# Kita pakai shell form untuk CMD agar variable expansion ${PORT} bekerja
CMD php artisan config:cache && \
    php artisan view:cache && \
    apache2-foreground
