# 📚 Bookuy

![Bookuy Banner](public/images/logo-color-full.png)

> **Platform Jual-Beli & Penyewaan Buku Bekas Khusus Mahasiswa**

[![Laravel](https://img.shields.io/badge/Laravel-12.0-red?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php)](https://php.net)

## 📖 Tentang Bookuy

**Bookuy** adalah solusi inovatif untuk ekosistem akademik yang lebih hemat dan ramah lingkungan. Aplikasi ini menjembatani kesenjangan antara mahasiswa yang memiliki buku tak terpakai dengan mereka yang membutuhkannya.

Kami percaya pendidikan harus dapat diakses oleh semua orang. Dengan fitur **Jual-Beli** dan **Sewa**, Bookuy tidak hanya membuat literatur lebih terjangkau, tetapi juga mendukung gerakan *go-green* dengan mengurangi limbah kertas melalui penggunaan kembali buku.

---

## 🚀 Fitur Utama

Bookuy dirancang dengan pendekatan *user-centric* untuk memberikan pengalaman terbaik:

### 🛒 Transaksi Fleksibel
* **Jual & Beli:** Temukan buku bekas berkualitas dengan harga miring.
* **Sewa Buku:** Hemat biaya dengan menyewa buku per semester (tersedia opsi durasi).

### 🔍 Pencarian Cerdas
* **Filter Lengkap:** Cari buku berdasarkan Judul, Penulis, Kategori Mata Kuliah, hingga Semester.
* **Kondisi Buku:** Transparansi kondisi buku (Baru, Bekas Premium, Bekas Usang).

### 📱 Pengalaman Pengguna
* **Mobile-First Design:** Tampilan responsif yang nyaman diakses melalui smartphone.
* **Manajemen Keranjang:** Simpan item impian Anda dan checkout sekaligus.
* **Riwayat Transaksi:** Pantau status pembelian dan penjualan secara real-time.

### ⭐ Reputasi & Keamanan
* **Rating & Review:** Lihat ulasan pembeli sebelumnya untuk memastikan kredibilitas penjual dan kualitas buku.
* **Autentikasi Aman:** Sistem login dan registrasi yang terlindungi.

---

## 🛠️ Teknologi yang Digunakan

Aplikasi ini dibangun di atas fondasi teknologi modern untuk memastikan performa dan skalabilitas:

| Teknologi | Deskripsi |
| :--- | :--- |
| **Laravel 12** | Framework PHP terbaru yang ekspresif dan elegan. |
| **PHP 8.2** | Bahasa pemrograman server-side yang efisien. |
| **MySQL** | Sistem manajemen database relasional yang handal. |
| **Blade Templates** | Mesin templating bawaan Laravel yang kuat. |
| **Tailwind CSS** | Framework CSS *utility-first* untuk desain UI yang cepat dan modern. |

---

## 👥 Kontributor

Terima kasih kepada tim pengembang yang telah berkontribusi dalam pembangunan proyek Bookuy (PPPL B).

| No | Nama | NRP | Username GitHub |
| :-- | :--- | :--- | :--- |
| 1 | Fadhiil Akmal Hamizan | 5026231128 | [Axmalz](https://github.com/Axmalz) |
| 2 | James Melvin Chandra | - | - |
| 3 | Ananda Donelly Reksana | - | - |
| 4 | Hafiyyuddin Ahmad | - | - |
| 5 | Putu Arya Yubi Wirayudha | - | - |

---

## 💻 Panduan Instalasi (Lokal)

Ikuti langkah berikut untuk menjalankan proyek di komputer Anda:

### Prasyarat
Pastikan perangkat Anda sudah terinstal:
* PHP >= 8.2
* Composer
* MySQL
* Node.js & NPM (Opsional untuk aset frontend)

### Langkah-langkah

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/Axmalz/bookuy.git](https://github.com/Axmalz/bookuy.git)
    cd bookuy
    ```

2.  **Instal Dependensi PHP**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Sesuaikan konfigurasi database di file `.env`:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=bookuy_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

5.  **Migrasi Database & Seeding**
    Jalankan perintah ini untuk membuat tabel dan mengisi data dummy (User, Buku, Kategori, dll):
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Setup Frontend (Opsional)**
    Jika ingin mengedit aset CSS/JS:
    ```bash
    npm install && npm run build
    ```

7.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

8.  **Selesai!**
    Buka browser dan kunjungi: `http://localhost:8000`

---

## 📂 Struktur Proyek

* **`app/Http/Controllers/`**: Logika utama aplikasi (Chat, Checkout, Product, dll).
* **`app/Models/`**: Representasi data (User, Book, Order, Review).
* **`database/migrations/`**: Struktur skema database.
* **`resources/views/`**: Halaman antarmuka pengguna (Blade).
* **`routes/web.php`**: Definisi rute aplikasi.

---
