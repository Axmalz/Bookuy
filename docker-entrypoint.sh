#!/bin/bash

# PENTING: Kita mematikan 'set -e' (exit on error).
# Tujuannya: Agar container TIDAK MATI jika migrasi database gagal.
# Jika container mati -> Railway memberi error 502.
# Jika container tetap hidup -> Laravel memberi error 500 (kita bisa baca errornya apa).
set +e

echo "--- 🚀 STARTING RAILWAY DEPLOYMENT (DEBUG MODE) ---"

# 1. Deteksi Port dari Railway
# Railway akan menyuntikkan variabel $PORT secara otomatis.
if [ -z "$PORT" ]; then
    echo "⚠️  WARNING: \$PORT variable not found. Defaulting to 8080."
    PORT=8080
else
    echo "✅ Railway assigned PORT: $PORT"
fi

# 2. Konfigurasi Apache secara Eksplisit
# Kita ubah 'Listen 80' menjadi 'Listen $PORT' secara spesifik agar tidak salah replace.
echo "🔧 Configuring Apache Ports..."
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# 3. Jalankan Migrasi Database (Dengan Error Handling)
echo "📦 Running Database Migrations..."
php artisan migrate --force

# Cek status perintah migrate (exit code)
if [ $? -ne 0 ]; then
    echo "❌ MIGRATION FAILED! Server will start anyway so you can see the error logs."
    echo "   -> Possible causes: Wrong DB credentials, DB host unreachable, or timeout."
else
    echo "✅ Migrations success."
fi

# 4. Optimasi Laravel
echo "🔥 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Perbaiki Permission (Penting untuk Storage)
echo "🔒 Fixing Permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 6. Jalankan Apache
echo "🚀 Starting Apache in foreground on port $PORT..."
# Hapus PID file apache jika ada (sisa crash sebelumnya)
rm -f /var/run/apache2/apache2.pid
exec apache2-foreground
