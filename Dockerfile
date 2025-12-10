FROM php:8.2-apache

# 1. Install dependencies sistem, Node.js, dan utility dos2unix
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    dos2unix

# 2. Bersihkan cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Enable Apache mod_rewrite (Wajib untuk Laravel)
RUN a2enmod rewrite

# 5. Atur Document Root ke /public (Standar Laravel)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Set direktori kerja
WORKDIR /var/www/html

# 8. Salin semua file project
COPY . .

# 9. Install dependensi PHP (Composer)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 10. Install dependensi JS dan Build Assets
RUN npm install
RUN npm run build

# 11. Atur permission folder storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 12. Salin script entrypoint
COPY docker-entrypoint.sh /usr/local/bin/

# 13. Perbaiki line endings (Windows -> Linux) & set executable
RUN dos2unix /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 14. Expose port (Railway akan mengabaikan ini tapi bagus untuk dokumentasi)
EXPOSE 8080

# 15. Jalankan script entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
