#!/bin/bash
set +e

echo "🚀 Starting Application (Minimal Mode)..."

# 1. Konfigurasi Port Apache
if [ -z "$PORT" ]; then
    PORT=8080
fi
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 2. Fix Permission
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 3. HAPUS Optimasi Cache Sementara
# Kita matikan dulu config:cache, route:cache, dll agar booting lebih aman & cepat.
# Laravel akan membaca .env secara langsung.
echo "⚠️ Skipping optimization commands to ensure stability..."
php artisan optimize:clear

# 4. Jalankan Apache
echo "🔥 Starting Apache on port $PORT..."
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
