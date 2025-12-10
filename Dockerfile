FROM php:8.2-cli

# 1. Install dependencies sistem dan Node.js (Penting untuk Vite/Tailwind)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# 2. Bersihkan cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set direktori kerja
WORKDIR /var/www/html

# 6. Salin semua file project
COPY . .

# 7. Install dependensi PHP (Composer)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 8. Install dependensi JS dan Build Assets (Penting untuk Tailwind)
RUN npm install
RUN npm run build

# 9. Atur permission folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Salin dan set permission untuk script entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 11. Expose port (hanya untuk dokumentasi, Railway mengabaikan ini dan menyuntikkan $PORT)
EXPOSE 8080

# 12. Jalankan script entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
