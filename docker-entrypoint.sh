#!/bin/bash

# PENTING: Kita mematikan 'set -e' (exit on error).
set +e

echo "--- 🚀 STARTING RAILWAY DEPLOYMENT (FAST BOOT MODE) ---"

# 1. Deteksi Port dari Railway
if [ -z "$PORT" ]; then
    echo "⚠️  WARNING: \$PORT variable not found. Defaulting to 8080."
    PORT=8080
else
    echo "✅ Railway assigned PORT: $PORT"
fi

# 2. Konfigurasi Apache secara Eksplisit
echo "🔧 Configuring Apache Ports..."
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 3. SKIP MIGRASI OTOMATIS (SOLUSI TIMEOUT)
# Kita matikan proses ini karena memakan waktu > 100 detik (seeding)
# yang menyebabkan Railway mematikan server (502 Gateway Timeout) sebelum Apache nyala.
#
# echo "📦 Running Database Migrations..."
# php artisan migrate --force

echo "⚠️ SKIPPING MIGRATION to ensure server boots fast. Run migration manually if needed."

# 4. Optimasi Laravel
echo "🔥 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Perbaiki Permission
echo "🔒 Fixing Permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 6. Jalankan Apache
echo "🚀 Starting Apache in foreground on port $PORT..."
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
