#!/bin/bash

# Berhenti jika ada error
set -e

# 1. Jalankan migrasi database otomatis (force untuk production)
echo "Running database migrations..."
php artisan migrate --force

# 2. Cache konfigurasi dan route untuk performa
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Jalankan server Laravel
# PENTING: Gunakan host 0.0.0.0 dan port dari environment variable $PORT
echo "Starting Laravel server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
