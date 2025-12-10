#!/bin/bash

# Pastikan script tidak berhenti jika ada error kecil, tapi catat errornya
set -e

echo "🚀 Starting deployment with Apache..."

# 1. Konfigurasi Port Apache (KUNCI ANTI-502)
# Railway memberikan PORT dinamis. Kita harus memaksa Apache mendengarkan port tersebut.
# Default ke 8080 jika variabel PORT tidak ada.
PORT=${PORT:-8080}
echo "🔧 Configuring Apache to listen on port $PORT..."
sed -i "s/80/$PORT/g" /etc/apache2/sites-enabled/000-default.conf /etc/apache2/ports.conf

# 2. Jalankan Migrasi Database
echo "📦 Running database migrations..."
# Gunakan --force karena ini mode production
php artisan migrate --force || echo "⚠️ Migration failed! Check DB credentials."

# 3. Optimasi Laravel
echo "🔥 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Fix Permission (Jaga-jaga)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 5. Jalankan Apache di Foreground
echo "🚀 Server ready!"
exec apache2-foreground
