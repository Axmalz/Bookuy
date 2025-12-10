#!/bin/bash
set +e

echo "🚀 Starting Application..."

# 1. Konfigurasi Port Apache (CRITICAL)
if [ -z "$PORT" ]; then
    PORT=8080
fi
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 2. Fix Permission Cepat
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Jalankan Apache LANGSUNG (Tanpa artisan optimize di sini)
# Optimasi sebaiknya dilakukan di Dockerfile saat build, bukan saat runtime start
echo "🔥 Starting Apache on port $PORT..."
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
