## Tentang Bookuy

Bookuy adalah platform inovatif yang dirancang khusus untuk mahasiswa guna memfasilitasi jual-beli dan penyewaan buku bekas. Aplikasi ini bertujuan untuk membuat buku pelajaran lebih terjangkau dan mudah diakses, sekaligus mengurangi limbah kertas dengan mendorong penggunaan kembali buku.

Kami percaya bahwa pendidikan harus dapat diakses oleh semua orang, dan Bookuy hadir untuk menjembatani kesenjangan antara mahasiswa yang membutuhkan buku dengan mereka yang memiliki buku yang tidak lagi digunakan.

## Fitur Utama

Bookuy hadir dengan serangkaian fitur yang dirancang untuk memberikan pengalaman pengguna yang mulus dan intuitif:

- Jual & Sewa Buku: Pengguna dapat memilih untuk membeli buku secara permanen atau menyewanya untuk jangka waktu tertentu (semester).

- Pencarian & Filter Canggih: Temukan buku dengan cepat berdasarkan judul, penulis, atau kategori. Gunakan filter harga, kondisi, dan semester untuk hasil yang lebih spesifik.

- Desain Mobile-First: Antarmuka pengguna yang dioptimalkan untuk perangkat mobile, memberikan pengalaman seperti aplikasi native.

- Sistem Rating & Review: Transparansi kualitas buku dan reputasi penjual melalui sistem ulasan yang komprehensif.

- Keranjang Belanja: Kelola buku yang ingin dibeli atau disewa dalam satu tempat sebelum checkout.

- Manajemen Profil: Atur informasi pribadi dan riwayat transaksi.

## Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan stack teknologi modern yang handal:

- Laravel 10: Framework PHP yang ekspresif dan elegan.

- MySQL: Sistem manajemen database relasional yang kuat.

- Blade Templates: Mesin templating yang sederhana namun kuat.

- Tailwind CSS: Framework CSS utility-first untuk desain UI yang cepat dan kustom.

## Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda:

### Prasyarat

Pastikan Anda telah menginstal:

PHP >= 8.1

Composer

Node.js & NPM (Opsional)

MySQL

### Langkah-langkah

### 1. Clone Repositori:

git clone [https://github.com/username/bookuy.git](https://github.com/username/bookuy.git)
cd bookuy


### 2. Instal Dependensi PHP:

composer install


### 3. Konfigurasi Environment:

Salin file contoh .env dan sesuaikan dengan konfigurasi database Anda.

cp .env.example .env


Buka file .env dan atur kredensial database:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookuy_db
DB_USERNAME=root
DB_PASSWORD=


### 4. Generate App Key:

php artisan key:generate


### 5. Migrasi Database & Seeding:

Jalankan perintah ini untuk membuat tabel dan mengisi data dummy (termasuk 30 user random, buku, kategori, dan review).

php artisan migrate:fresh --seed


### 6. Jalankan Server:

php artisan serve


### 7. Akses Aplikasi:

Buka browser Anda dan kunjungi http://localhost:8000.

## Struktur Proyek

Gambaran singkat struktur folder utama dalam proyek Bookuy:

- app/Http/Controllers/: Logika aplikasi (AuthController, HomeController, ProductController, dll).

- app/Models/: Definisi model Eloquent (User, Book, Category, Review, Cart).

- database/migrations/: Skema database.

    - database/seeders/: Data awal untuk pengujian.

- resources/views/: Tampilan antarmuka pengguna (Blade templates).

- public/images/: Aset gambar statis.

## Kontributor

Terima kasih kepada tim pengembang yang luar biasa:

- James Melvin Chandra

- Fadhiil Akmal Hamizan

- Ananda Donelly Reksana

- Hafiyyuddin Ahmad

- Putu Arya Yubi Wirayudha
