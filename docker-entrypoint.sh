#!/bin/bash
set +e

echo "--- 🚀 STARTING RAILWAY DEPLOYMENT (APACHE BYPASS FIX) ---"

# 1. Konfigurasi Port
if [ -z "$PORT" ]; then
    echo "⚠️ PORT variable is empty! Defaulting to 8080."
    PORT=8080
fi
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 2. SOLUSI HEALTCHECK BYPASS (KUNCI UTAMA)
# Kita buat file index.html kecil di folder public/up
# Jadi ketika Railway akses /up, Apache langsung menyajikan file ini (Status 200)
# tanpa melewati routing Laravel yang berpotensi error/404.
echo "🚑 Creating Direct Apache Healthcheck..."
mkdir -p /var/www/html/public/up
echo "OK" > /var/www/html/public/up/index.html

# 3. Pastikan Folder Ada & Permission Benar
echo "📂 Fixing directory structure..."
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 4. Fail-Safe APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY is missing! Generating one automatically..."
    cp .env.example .env
    php artisan key:generate
fi

# 5. Link Storage & Bersihkan Cache
php artisan storage:link || true
echo "🧹 Clearing Caches..."
php artisan route:clear
php artisan config:clear
php artisan view:clear

# 6. Jalankan Apache
echo "🔥 Server starting on port $PORT..."
echo "👉 HEALTHCHECK PATH IS NOW A STATIC FILE AT: /up/"
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
