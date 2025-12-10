# Gunakan image PHP 8.2 resmi dengan Apache
FROM php:8.2-apache

# 1. Install Dependencies (Gabungan dari temanmu & standar Laravel)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsodium-dev \
    libpq-dev \
    default-mysql-client \
    default-libmysqlclient-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip sodium

# 2. Install Node.js (Dari temanmu - Penting jika ada build step)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 3. Enable Apache mod_rewrite (Wajib untuk Laravel)
RUN a2enmod rewrite

# 4. Set direktori kerja
WORKDIR /var/www/html

# 5. Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Copy File Project
COPY . .

# 7. Install PHP Dependencies (Production Mode)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 8. Install NPM Dependencies & Build (Jika pakai Vite)
# (Opsional: Jika build gagal karena resource, bisa dihapus dan build lokal dulu)
RUN npm install && npm run build

# 9. Set Permissions (Sangat Penting untuk Railway)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Konfigurasi Apache (Agar root ke /public)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 11. Allow .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 12. PORT Handling (Kunci Anti-502 di Railway)
# Mengganti port 80 default Apache dengan $PORT dari Railway (atau 8000)
RUN sed -s -i -e "s/80/\${PORT:-8000}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 13. Expose & CMD
EXPOSE 8000
CMD php artisan config:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    apache2-foreground
