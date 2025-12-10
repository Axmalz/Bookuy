FROM php:8.2-cli

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

# 3. Install ekstensi PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set direktori kerja
WORKDIR /var/www/html

# 6. Salin semua file project
COPY . .

# 7. Install dependensi PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 8. Install dependensi JS dan Build Assets
RUN npm install
RUN npm run build

# 9. Atur permission folder storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Salin script entrypoint
COPY docker-entrypoint.sh /usr/local/bin/

# 11. PERBAIKAN PENTING: Konversi line ending Windows ke Linux & set executable
RUN dos2unix /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 12. Expose port (info saja)
EXPOSE 8080

# 13. Jalankan script entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
