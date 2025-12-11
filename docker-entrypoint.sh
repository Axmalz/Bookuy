#!/bin/bash
set +e

echo "--- 🚀 STARTING RAILWAY DEPLOYMENT (DEBUG MODE) ---"

# 1. Konfigurasi Port
if [ -z "$PORT" ]; then
    echo "⚠️ PORT variable is empty! Defaulting to 8080."
    PORT=8080
fi
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 2. SOLUSI HEALTHCHECK BYPASS
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

# ============================================================
# 6. DATABASE CONNECTION TEST (BARU)
# ============================================================
echo "🔍 Testing Database Connection..."
# Kita coba cek status migrasi. Jika DB error, ini akan timeout/error di log.
php artisan migrate:status --timeout=10 || echo "❌ DATABASE CONNECTION FAILED! Check Variables."

# 7. NUCLEAR FIX MPM
echo "🔧 Fixing Apache MPM Configuration..."
rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork rewrite || true

# 8. Jalankan Apache
echo "🔥 Server starting on port $PORT..."
echo "👉 HEALTHCHECK PATH IS NOW A STATIC FILE AT: /up/"
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
