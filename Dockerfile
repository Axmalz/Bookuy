# Gunakan PHP-FPM
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

# ==============================================================================
# FIX 1: AKTIFKAN LOGGING NGINX KE CONSOLE (Agar Error 502 terlihat di Railway)
# ==============================================================================
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# ==============================================================================
# FIX 2: KONFIGURASI NGINX DENGAN BUFFER LEBIH BESAR
# ==============================================================================
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
    location ~ \.php$ { \
        include fastcgi_params; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name; \
        fastcgi_index index.php; \
        # Tuning Buffer untuk mencegah 502 pada response besar \
        fastcgi_buffers 16 16k; \
        fastcgi_buffer_size 32k; \
        fastcgi_read_timeout 300; \
    } \
    \
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

# 9. Create PHP Check File (Manual Test)
RUN echo "<?php echo 'PHP FPM is Working correctly. Version: ' . phpversion(); ?>" > /var/www/html/public/check.php

# ==============================================================================
# FIX 3: PERMISSION TOTAL (Chown Seluruh Project)
# Kita berikan seluruh folder ke www-data agar tidak ada isu permission
# ==============================================================================
RUN chown -R www-data:www-data /var/www/html

# 10. Copy & Prepare Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 11. Expose Port
EXPOSE 8080

# 12. Start Container
ENTRYPOINT ["docker-entrypoint.sh"]
