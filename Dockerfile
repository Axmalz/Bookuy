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

# 3. Enable Apache Rewrite & FIX MPM CONFLICT (PENTING!)
# Kita matikan mpm_event dan mpm_worker, lalu paksa mpm_prefork
RUN a2dismod mpm_event mpm_worker && a2enmod mpm_prefork rewrite

# 4. Configure Apache Document Root & ServerName
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

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
# Pastikan line ending benar (LF) dan permission execute
RUN dos2unix /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 12. Expose Port
EXPOSE 8080

# 13. Start Container
ENTRYPOINT ["docker-entrypoint.sh"]
