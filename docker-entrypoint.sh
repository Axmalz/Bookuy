#!/bin/bash
set -e # Exit immediately if a command exits with a non-zero status.

echo "--- 🚀 STARTING RAILWAY DEPLOYMENT (RUNTIME CONFIG) ---"

# 1. Tentukan Port
if [ -z "$PORT" ]; then
    echo "⚠️ PORT variable is empty! Defaulting to 8080."
    PORT=8080
fi

# 2. GENERATE NGINX CONFIG (Anti-Error Syntax)
# Kita tulis konfigurasi langsung ke file default sites-available
echo "📝 Generating Nginx Configuration for Port $PORT..."

cat > /etc/nginx/sites-available/default <<EOF
server {
    listen $PORT default_server;
    root /var/www/html/public;
    index index.php index.html;
    server_name _;

    # Log ke Console (Agar terlihat di Railway)
    error_log  /dev/stderr warn;
    access_log /dev/stdout;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        fastcgi_index index.php;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }

    # Healthcheck Bypass
    location /up/ {
        alias /var/www/html/public/up/;
        index index.html;
        access_log off;
    }
}
EOF

# 3. Setup Healthcheck File
mkdir -p /var/www/html/public/up
echo "OK" > /var/www/html/public/up/index.html
chown -R www-data:www-data /var/www/html/public/up

# 4. Setup Permissions & Cache
echo "📂 Setting permissions..."
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# 5. Laravel Setup
if [ -z "$APP_KEY" ]; then
    cp .env.example .env
    php artisan key:generate
fi
php artisan storage:link || true
php artisan optimize:clear

# 6. Test Nginx Configuration (DIAGNOSTIC)
echo "🔍 Testing Nginx Configuration..."
nginx -t

# 7. Start Services
echo "🔥 Starting PHP-FPM..."
# Konfigurasi agar log FPM muncul di console
echo "[global]
error_log = /proc/self/fd/2
daemonize = no
[www]
access.log = /proc/self/fd/2
catch_workers_output = yes
clear_env = no
listen = 127.0.0.1:9000" > /usr/local/etc/php-fpm.d/zz-docker.conf

# Jalankan FPM di background
php-fpm -D

echo "🔥 Starting Nginx..."
# Jalankan Nginx di foreground
nginx -g "daemon off;"
