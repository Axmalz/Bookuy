FROM php:8.2-apache

# 1. Install System Dependencies
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

# 2. Install PHP Extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Configure Apache & RAM Tuning (FIX 502 OOM)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Batasi jumlah proses Apache agar tidak memakan RAM (Pencegahan Crash 502)
RUN echo "<IfModule mpm_prefork_module>\n\
    StartServers             2\n\
    MinSpareServers          2\n\
    MaxSpareServers          4\n\
    MaxRequestWorkers        10\n\
    MaxConnectionsPerChild   0\n\
</IfModule>" > /etc/apache2/mods-enabled/mpm_prefork.conf

# 4. PHP Configuration
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/error-logging.ini \
    && echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/error-logging.ini \
    && echo "memory_limit = 128M" >> /usr/local/etc/php/conf.d/memory-limit.ini

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Set Working Directory
WORKDIR /var/www/html

# 7. Copy Application Files
COPY . .

# 8. Install PHP Dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 9. Install Node Dependencies & Build Assets
RUN npm install
RUN npm run build

# 10. Permission Setting
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Copy & Prepare Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN dos2unix /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 12. DEBUG FILE (Untuk Cek Server vs Laravel)
RUN echo "<?php echo '<h1>SERVER IS ALIVE</h1><p>PHP Version: ' . phpversion() . '</p>'; ?>" > /var/www/html/public/check.php

# 13. Clean Up MPM
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork rewrite

# 14. Expose & Start
EXPOSE 8080
ENTRYPOINT ["docker-entrypoint.sh"]
