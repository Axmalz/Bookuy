# Gunakan PHP-FPM (Lebih stabil untuk Container)
FROM php:8.2-fpm

# 1. Install Nginx & System Dependencies
RUN apt-get update && apt-get install -y \
    nginx \
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

# 2. Install PHP Extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set Working Directory
WORKDIR /var/www/html

# 5. Copy Application Files
COPY . .

# 6. Install Dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# 7. Create PHP Check File
RUN echo "<?php echo 'PHP FPM OK. Version: ' . phpversion(); ?>" > /var/www/html/public/check.php

# 8. Permission Setting (PENTING: Berikan ke www-data)
RUN chown -R www-data:www-data /var/www/html

# 9. Prepare Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
# Hapus karakter Windows (\r) jika ada, dan buat executable
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 10. Expose Port
EXPOSE 8080

# 11. Start
ENTRYPOINT ["docker-entrypoint.sh"]
