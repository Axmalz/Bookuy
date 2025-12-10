#!/bin/bash
set +e

echo "🚀 Starting Application..."

# 1. Konfigurasi Port Apache
if [ -z "$PORT" ]; then
    PORT=8080
fi
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 2. Fix Permission
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Optimasi Laravel (PENTING: Jalankan di sini, bukan di Dockerfile)
# Agar mengambil variabel environment yang BENAR dari Railway.
echo "🔥 Optimizing Configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Jalankan Apache
echo "🔥 Starting Apache on port $PORT..."
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
