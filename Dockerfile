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

# 2. Install PHP Extensions (Standar & Stabil)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Configure Apache Environment
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
# Aktifkan .htaccess
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf
RUN a2enmod rewrite

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set Working Directory
WORKDIR /var/www/html

# 6. Copy Application Files (Sebagai Root dulu)
COPY . .

# 7. Install Dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# 8. PERMISSION FIX (KRUSIAL UNTUK MENGHINDARI 502)
# Kita ubah kepemilikan SEMUA file ke www-data agar Apache punya akses penuh
RUN chown -R www-data:www-data /var/www/html

# 9. Create Check File (Testing Isolation)
RUN echo "<?php echo '<h1>SERVER ALIVE</h1>'; phpinfo(); ?>" > /var/www/html/public/check.php \
    && chown www-data:www-data /var/www/html/public/check.php

# 10. Copy & Prepare Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN dos2unix /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 11. Fix Apache MPM (Hapus konflik modul)
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork rewrite

# 12. Expose & Start
EXPOSE 8080
ENTRYPOINT ["docker-entrypoint.sh"]
