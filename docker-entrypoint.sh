#!/bin/bash
set +e

echo "--- 🚀 STARTING RAILWAY DEPLOYMENT (FINAL FIX) ---"

# 1. Konfigurasi Port
if [ -z "$PORT" ]; then
    echo "⚠️ PORT variable is empty! Defaulting to 8080."
    PORT=8080
fi
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 2. Pastikan Folder Ada & Permission Benar
echo "📂 Fixing directory structure & permissions..."
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Fail-Safe APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY is missing! Generating one automatically..."
    cp .env.example .env
    php artisan key:generate
    php artisan config:cache
else
    echo "✅ APP_KEY found."
fi

# 4. Link Storage
php artisan storage:link || true

# 5. INJECT HEALTHCHECK ROUTE (SOLUSI ERROR 404)
# Kita tambahkan route /up secara paksa agar Railway tidak error 404.
if ! grep -q "Route::get('/up'" routes/web.php; then
    echo "🚑 Injecting /up healthcheck route to routes/web.php..."
    echo "" >> routes/web.php
    echo "Route::get('/up', function () { return response('OK', 200); });" >> routes/web.php
fi

# 6. Hapus Cache Agar Bersih
echo "🧹 Clearing Caches..."
php artisan optimize:clear

# 7. Jalankan Apache
echo "🔥 Server starting on port $PORT..."
echo "👉 HEALTHCHECK PATH SHOULD BE: /up"
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
