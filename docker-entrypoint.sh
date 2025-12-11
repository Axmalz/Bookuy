#!/bin/bash
set +e

echo "--- 🚀 STARTING RAILWAY DEPLOYMENT (NGINX + PHP-FPM) ---"

# 1. Konfigurasi Port Nginx Dinamis
if [ -z "$PORT" ]; then
    echo "⚠️ PORT variable is empty! Defaulting to 8080."
    PORT=8080
fi
# Update port di config nginx sesuai environment Railway
sed -i "s/listen 8080/listen ${PORT}/g" /etc/nginx/sites-available/default
echo "✅ Nginx configured to listen on port $PORT"

# 2. Setup Healthcheck Bypass
mkdir -p /var/www/html/public/up
echo "OK" > /var/www/html/public/up/index.html
chown -R www-data:www-data /var/www/html/public/up

# 3. Setup Folder & Permissions
echo "📂 Fixing directory structure..."
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# 4. Fail-Safe APP_KEY
if [ -z "$APP_KEY" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# 5. Optimization
php artisan storage:link || true
php artisan optimize:clear
# Optional: php artisan migrate --force (Hati-hati di production)

# 6. Start Services
echo "🔥 Starting PHP-FPM..."
# Jalankan PHP-FPM di background (Daemon)
php-fpm -D

echo "🔥 Starting Nginx..."
# Jalankan Nginx di foreground agar container tetap hidup
nginx -g "daemon off;"
