#!/bin/bash

# Hapus "set -e" agar container tidak mati mendadak jika ada command gagal
# set -e 

echo "🚀 Starting deployment process..."

# 1. Jalankan migrasi database (Gunakan try/catch logic sederhana)
echo "Running database migrations..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo "✅ Migration successful."
else
    echo "⚠️ MIGRATION FAILED! Check your database credentials in Railway variables."
    # Kita lanjut saja ke bawah agar server tetap nyala dan bisa menampilkan error log
fi

# 2. Optimasi Cache
echo "Caching configuration..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Jalankan server Laravel
# Menggunakan PORT dari env variable Railway
echo "🔥 Starting Laravel server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT