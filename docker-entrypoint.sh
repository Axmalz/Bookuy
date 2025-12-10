#!/bin/bash
set +e

echo "--- 🚀 STARTING DEPLOYMENT (RECOVERY MODE) ---"

# 1. Debugging Port (Pastikan kita menggunakan Port dari Railway)
echo "🔍 Debugging: Detected PORT variable as: '$PORT'"
if [ -z "$PORT" ]; then
    echo "⚠️ PORT variable is empty! Defaulting to 8080."
    PORT=8080
fi

# 2. Konfigurasi Port Apache
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 3. BUAT STRUKTUR FOLDER (SOLUSI KRUSIAL)
# Folder ini sering hilang saat upload git, menyebabkan Error 500 saat live.
echo "📂 Creating storage directory structure..."
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# 4. IZIN AKSES AGRESIF (Full Write Access)
# Kita set 777 sementara untuk memastikan 100% Laravel bisa menulis file.
echo "🔒 Forcing aggressive permissions..."
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 5. Hapus Cache Sisa Build
echo "🧹 Clearing Config Cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. Jalankan Apache
echo "🔥 Starting Apache on port $PORT..."
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
