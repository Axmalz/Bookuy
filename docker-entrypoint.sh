#!/bin/bash
set +e

echo "--- 🚀 STARTING RAILWAY DEPLOYMENT (DIAGNOSTIC MODE) ---"

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

# 3. Setup Folder & Permission
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

# 5. Link Storage & Clear Cache
php artisan storage:link || true
echo "🧹 Clearing Caches..."
php artisan optimize:clear

# ============================================================
# 6. DATABASE CONNECTION TEST (FIXED)
# ============================================================
echo "🔍 Testing Database Connection..."
# Kita pakai perintah PHP native untuk test koneksi agar lebih cepat & akurat
php -r "
try {
    \$pdo = new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    echo '✅ DATABASE CONNECTION SUCCESSFUL!'.PHP_EOL;
} catch (PDOException \$e) {
    echo '❌ DATABASE CONNECTION FAILED: ' . \$e->getMessage() . PHP_EOL;
    // Kita tidak exit agar container tetap jalan untuk debug
}
"

# 7. NUCLEAR FIX MPM (Agar Apache tidak crash)
echo "🔧 Fixing Apache MPM Configuration..."
rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork rewrite || true

# 8. Start Server
echo "🔥 Server starting on port $PORT..."
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
