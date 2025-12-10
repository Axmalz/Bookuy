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

# 3. Fail-Safe APP_KEY (Penyebab umum Error 500)
# Jika APP_KEY tidak ada di env Railway, kita buatkan sementara agar server tidak crash.
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY is missing! Generating one automatically..."
    cp .env.example .env
    php artisan key:generate
    php artisan config:cache
else
    echo "✅ APP_KEY found."
fi

# 4. Link Storage (Agar gambar muncul)
php artisan storage:link || true

# 5. Hapus Cache Agar Bersih
echo "🧹 Clearing Caches..."
php artisan optimize:clear

# 6. Jalankan Apache
echo "🔥 Server starting on port $PORT..."
echo "👉 HEALTHCHECK PATH SHOULD BE: /up"
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
