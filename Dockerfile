# Gunakan PHP-FPM (Bukan Apache) - Jauh lebih ringan & stabil
FROM php:8.2-fpm

# 1. Install Nginx & Dependencies
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
    npm

# 2. Install PHP Extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Setup Nginx Configuration (Inject Config Langsung)
# Kita buat konfigurasi server block Nginx yang support Laravel & Healthcheck
RUN echo 'server { \
    listen 8080 default_server; \
    root /var/www/html/public; \
    index index.php index.html; \
    server_name _; \
    client_max_body_size 64M; \
    \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    \
    # Handle PHP Scripts via FPM \
    location ~ \.php$ { \
        include fastcgi_params; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        fastcgi_index index.php; \
    } \
    \
    # Healthcheck Bypass khusus Railway \
    location /up/ { \
        alias /var/www/html/public/up/; \
        index index.html; \
        access_log off; \
    } \
}' > /etc/nginx/sites-available/default

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set Working Directory
WORKDIR /var/www/html

# 6. Copy Application Files
COPY . .

# 7. Install PHP Dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 8. Install Node Dependencies & Build Assets
RUN npm install
RUN npm run build

# 9. Permission Setting (Wajib untuk Nginx & Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Copy & Prepare Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
# Fix Windows line endings just in case
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 11. Expose Port (Railway akan override ini, tapi standar 8080)
EXPOSE 8080

# 12. Start Container
ENTRYPOINT ["docker-entrypoint.sh"]
