<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\Review;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 50 user sekaligus untuk memastikan stok user cukup untuk semua buku manual
        // Ini akan menghasilkan user dengan ID berurutan (misal: 1, 2, 3, 4, ... dst)
        $sellers = User::factory()->count(50)->create();
        $i = 0; // Index iterator untuk mengambil user secara berurutan

        Book::create([
            'judul_buku' => 'Fundamental Manajemen Proses Bisnis',
            'nama_penulis' => 'Marlon Dumas',
            'harga_beli' => 150000,
            'harga_sewa' => 45000,
            'stok_beli' => 5,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/fundamental-manajemen-proses-bisnis-front.jpg',
                'book-images/fundamental-manajemen-proses-bisnis-back.jpg',
                'book-images/fundamental-manajemen-proses-bisnis-in.jpg',
            ],
            'deskripsi_buku' => 'Buku ini membahas konsep dasar manajemen proses bisnis secara menyeluruh. Sangat cocok untuk mahasiswa jurusan sistem informasi dan manajemen yang ingin memahami alur proses bisnis modern dan cara mengoptimalkannya.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 1,
            'jumlah_halaman' => 320,
            'semester' => '3',
        ]);

        Book::create([
            'judul_buku' => 'Borland Delphi 7',
            'nama_penulis' => 'Andi',
            'harga_beli' => 85000,
            'harga_sewa' => 20000,
            'stok_beli' => 8,
            'stok_sewa' => 3,
            'gambar_buku' => [
                'book-images/borland-delphi-7-front.jpg',
                'book-images/borland-delphi-7-back.jpg',
                'book-images/borland-delphi-7-in.jpg',
            ],
            'deskripsi_buku' => 'Panduan praktis pemrograman menggunakan Borland Delphi 7. Buku ini mencakup dasar-dasar pemrograman visual, database, dan pembuatan laporan. Cocok untuk pemula yang ingin belajar Pascal.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Sleman, DI Yogyakarta',
            'category_id' => 2,
            'jumlah_halaman' => 250,
            'semester' => '2',
        ]);

        Book::create([
            'judul_buku' => 'Ubuntu 12',
            'nama_penulis' => 'Andi',
            'harga_beli' => 70000,
            'harga_sewa' => 15000,
            'stok_beli' => 10,
            'stok_sewa' => 4,
            'gambar_buku' => [
                'book-images/ubuntu-12-front.jpg',
                'book-images/ubuntu-12-back.jpg',
                'book-images/ubuntu-12-in.jpg',
            ],
            'deskripsi_buku' => 'Buku panduan lengkap penggunaan sistem operasi Ubuntu versi 12. Membahas instalasi, konfigurasi jaringan, hingga penggunaan aplikasi perkantoran open source.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Surabaya, Jawa Timur',
            'category_id' => 2,
            'jumlah_halaman' => 180,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Mudah Membuat Aplikasi Android',
            'nama_penulis' => 'Stephanus Hermawan S',
            'harga_beli' => 120000,
            'harga_sewa' => 30000,
            'stok_beli' => 12,
            'stok_sewa' => 5,
            'gambar_buku' => [
                'book-images/mudah-membuat-aplikasi-android-front.jpg',
                'book-images/mudah-membuat-aplikasi-android-back.jpg',
                'book-images/mudah-membuat-aplikasi-android-in.jpg',
            ],
            'deskripsi_buku' => 'Pelajari cara membuat aplikasi Android dari nol dengan mudah. Buku ini dilengkapi dengan contoh kode program dan studi kasus pembuatan aplikasi sederhana.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Jakarta Barat, DKI Jakarta',
            'category_id' => 2,
            'jumlah_halaman' => 300,
            'semester' => '5',
        ]);

        Book::create([
            'judul_buku' => 'Rekayasa Sistem Pengenalan Wajah',
            'nama_penulis' => 'Hanif Al Fatta',
            'harga_beli' => 135000,
            'harga_sewa' => 35000,
            'stok_beli' => 4,
            'stok_sewa' => 1,
            'gambar_buku' => [
                'book-images/rekayasa-sistem-pengenalan-wajah-front.jpg',
                'book-images/rekayasa-sistem-pengenalan-wajah-back.jpg',
                'book-images/rekayasa-sistem-pengenalan-wajah-in.jpg',
            ],
            'deskripsi_buku' => 'Membahas teori dan implementasi sistem pengenalan wajah menggunakan metode-metode kecerdasan buatan terkini. Referensi penting untuk mahasiswa tugas akhir.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Malang, Jawa Timur',
            'category_id' => 2,
            'jumlah_halaman' => 220,
            'semester' => '7',
        ]);

        Book::create([
            'judul_buku' => 'Finite Mathematics',
            'nama_penulis' => 'Raymond A. Barnett',
            'harga_beli' => 180000,
            'harga_sewa' => 48000,
            'stok_beli' => 3,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/finite-mathematics-front.jpg',
                'book-images/finite-mathematics-back.jpg',
                'book-images/finite-mathematics-in.jpg',
            ],
            'deskripsi_buku' => 'Textbook klasik mengenai matematika terbatas. Mencakup topik aljabar linier, probabilitas, dan statistik dasar dengan pendekatan aplikatif.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Medan, Sumatera Utara',
            'category_id' => 4,
            'jumlah_halaman' => 450,
            'semester' => '2',
        ]);

        Book::create([
            'judul_buku' => 'The Essential of Modern Mathematics',
            'nama_penulis' => 'Alexander',
            'harga_beli' => 160000,
            'harga_sewa' => 40000,
            'stok_beli' => 6,
            'stok_sewa' => 0,
            'gambar_buku' => [
                'book-images/the-essential-of-modern-mathematics-front.jpg',
                'book-images/the-essential-of-modern-mathematics-back.jpg',
                'book-images/the-essential-of-modern-mathematics-in.jpg',
            ],
            'deskripsi_buku' => 'Rangkuman esensial matematika modern. Buku ini menyajikan konsep-konsep abstrak dengan bahasa yang mudah dipahami.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Depok, Jawa Barat',
            'category_id' => 4,
            'jumlah_halaman' => 380,
            'semester' => '3',
        ]);

        Book::create([
            'judul_buku' => 'Elementary School Mathematics',
            'nama_penulis' => 'Joseph',
            'harga_beli' => 95000,
            'harga_sewa' => 25000,
            'stok_beli' => 15,
            'stok_sewa' => 5,
            'gambar_buku' => [
                'book-images/elementary-school-mathematics-front.jpg',
                'book-images/elementary-school-mathematics-back.jpg',
                'book-images/elementary-school-mathematics-in.jpg',
            ],
            'deskripsi_buku' => 'Buku referensi pendidikan matematika dasar. Cocok untuk calon guru atau pengajar yang ingin mendalami metode pengajaran matematika.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Semarang, Jawa Tengah',
            'category_id' => 4,
            'jumlah_halaman' => 200,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Kalkulus 1',
            'nama_penulis' => 'Albert',
            'harga_beli' => 110000,
            'harga_sewa' => 28000,
            'stok_beli' => 9,
            'stok_sewa' => 3,
            'gambar_buku' => [
                'book-images/kalkulus-1-front.jpg',
                'book-images/kalkulus-1-back.jpg',
                'book-images/kalkulus-1-in.jpg',
            ],
            'deskripsi_buku' => 'Materi dasar Kalkulus 1 meliputi limit, turunan, dan integral. Disajikan dengan banyak latihan soal untuk memperkuat pemahaman.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Bogor, Jawa Barat',
            'category_id' => 4,
            'jumlah_halaman' => 350,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Kalkulus Lanjut Edisi 2',
            'nama_penulis' => 'Wikaria Gazali',
            'harga_beli' => 125000,
            'harga_sewa' => 32000,
            'stok_beli' => 7,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/kalkulus-lanjut-edisi-2-front.jpg',
                'book-images/kalkulus-lanjut-edisi-2-back.jpg',
                'book-images/kalkulus-lanjut-edisi-2-in.jpg',
            ],
            'deskripsi_buku' => 'Lanjutan dari Kalkulus 1, membahas deret tak hingga, persamaan diferensial dasar, dan kalkulus vektor. Edisi revisi dengan soal-soal baru.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 4,
            'jumlah_halaman' => 400,
            'semester' => '2',
        ]);

        Book::create([
            'judul_buku' => 'Kalkulus Belajar Super Cepat',
            'nama_penulis' => 'Elliot Nendelson',
            'harga_beli' => 90000,
            'harga_sewa' => 22000,
            'stok_beli' => 10,
            'stok_sewa' => 4,
            'gambar_buku' => [
                'book-images/kalkulus-belajar-super-cepat-front.jpg',
                'book-images/kalkulus-belajar-super-cepat-back.jpg',
                'book-images/kalkulus-belajar-super-cepat-in.jpg',
            ],
            'deskripsi_buku' => 'Metode ringkas memahami kalkulus dalam waktu singkat. Berisi trik dan tips penyelesaian soal kalkulus dengan cepat.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Surakarta, Jawa Tengah',
            'category_id' => 4,
            'jumlah_halaman' => 150,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Mathematical Systems Theory',
            'nama_penulis' => 'G. J. Olsder',
            'harga_beli' => 195000,
            'harga_sewa' => 49000,
            'stok_beli' => 2,
            'stok_sewa' => 1,
            'gambar_buku' => [
                'book-images/mathematical-systems-theory-front.jpg',
                'book-images/mathematical-systems-theory-back.jpg',
                'book-images/mathematical-systems-theory-in.jpg',
            ],
            'deskripsi_buku' => 'Buku teks tingkat lanjut tentang teori sistem matematika. Wajib dimiliki oleh mahasiswa pascasarjana matematika terapan.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Yogyakarta, DI Yogyakarta',
            'category_id' => 4,
            'jumlah_halaman' => 480,
            'semester' => '6',
        ]);

        Book::create([
            'judul_buku' => 'An Introduction to Statistical Physics for Students',
            'nama_penulis' => 'A. J. Pointon',
            'harga_beli' => 175000,
            'harga_sewa' => 42000,
            'stok_beli' => 3,
            'stok_sewa' => 1,
            'gambar_buku' => [
                'book-images/an-introduction-to-statistical-physics-for-students-front.jpg',
                'book-images/an-introduction-to-statistical-physics-for-students-back.jpg',
                'book-images/an-introduction-to-statistical-physics-for-students-in.jpg',
            ],
            'deskripsi_buku' => 'Pengantar fisika statistik untuk mahasiswa sarjana. Menjelaskan konsep termodinamika dari sudut pandang mikroskopis.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 5,
            'jumlah_halaman' => 310,
            'semester' => '5',
        ]);

        Book::create([
            'judul_buku' => 'Mekanika Fluida 1',
            'nama_penulis' => 'Ir. M. Orianto, BSE.',
            'harga_beli' => 130000,
            'harga_sewa' => 33000,
            'stok_beli' => 6,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/mekanika-fluida-1-front.jpg',
                'book-images/mekanika-fluida-1-back.jpg',
                'book-images/mekanika-fluida-1-in.jpg',
            ],
            'deskripsi_buku' => 'Dasar-dasar mekanika fluida, termasuk statika fluida dan dinamika fluida dasar. Dilengkapi contoh soal teknik sipil dan mesin.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Surabaya, Jawa Timur',
            'category_id' => 5,
            'jumlah_halaman' => 280,
            'semester' => '4',
        ]);

        Book::create([
            'judul_buku' => 'Teori Ketidakpastian',
            'nama_penulis' => 'B. Darmawan Djonoputro',
            'harga_beli' => 88000,
            'harga_sewa' => 21000,
            'stok_beli' => 5,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/teori-ketidakpastian-front.jpg',
                'book-images/teori-ketidakpastian-back.jpg',
                'book-images/teori-ketidakpastian-in.jpg',
            ],
            'deskripsi_buku' => 'Membahas teori ralat dan ketidakpastian dalam pengukuran fisika. Buku wajib untuk praktikum fisika dasar.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 5,
            'jumlah_halaman' => 160,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Logique De La Relativite Restreinte',
            'nama_penulis' => 'S. J. Prokhovnik',
            'harga_beli' => 190000,
            'harga_sewa' => 46000,
            'stok_beli' => 1,
            'stok_sewa' => 0,
            'gambar_buku' => [
                'book-images/logique-de-la-relativite-restreinte-front.jpg',
                'book-images/logique-de-la-relativite-restreinte-back.jpg',
                'book-images/logique-de-la-relativite-restreinte-in.jpg',
            ],
            'deskripsi_buku' => 'Analisis mendalam logika di balik teori relativitas khusus. Edisi bahasa pengantar internasional untuk fisikawan teoritis.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Jakarta Selatan, DKI Jakarta',
            'category_id' => 5,
            'jumlah_halaman' => 290,
            'semester' => '6',
        ]);

        Book::create([
            'judul_buku' => 'Fisika Dasar',
            'nama_penulis' => 'Bambang Murdaka Eka Jati',
            'harga_beli' => 140000,
            'harga_sewa' => 38000,
            'stok_beli' => 8,
            'stok_sewa' => 4,
            'gambar_buku' => [
                'book-images/fisika-dasar-front.jpg',
                'book-images/fisika-dasar-back.jpg',
                'book-images/fisika-dasar-in.jpg',
            ],
            'deskripsi_buku' => 'Buku ajar fisika dasar untuk tingkat universitas. Mencakup mekanika, panas, bunyi, dan optik.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Yogyakarta, DI Yogyakarta',
            'category_id' => 5,
            'jumlah_halaman' => 420,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Fisika Dasar Listrik-Magnet',
            'nama_penulis' => 'Bambang Murdaka Eka Jati',
            'harga_beli' => 145000,
            'harga_sewa' => 39000,
            'stok_beli' => 7,
            'stok_sewa' => 3,
            'gambar_buku' => [
                'book-images/fisika-dasar-listrik-magnet-front.jpg',
                'book-images/fisika-dasar-listrik-magnet-back.jpg',
                'book-images/fisika-dasar-listrik-magnet-in.jpg',
            ],
            'deskripsi_buku' => 'Seri kedua dari fisika dasar yang fokus pada kelistrikan dan kemagnetan. Dilengkapi dengan ilustrasi dan contoh soal.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Yogyakarta, DI Yogyakarta',
            'category_id' => 5,
            'jumlah_halaman' => 380,
            'semester' => '2',
        ]);

        Book::create([
            'judul_buku' => 'Komputasi Simbolik Fisika Mekanika',
            'nama_penulis' => 'Khoe Yao Tung',
            'harga_beli' => 115000,
            'harga_sewa' => 29000,
            'stok_beli' => 4,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/komputasi-simbolik-fisika-mekanika-front.jpg',
                'book-images/komputasi-simbolik-fisika-mekanika-back.jpg',
                'book-images/komputasi-simbolik-fisika-mekanika-in.jpg',
            ],
            'deskripsi_buku' => 'Penerapan komputasi simbolik dalam menyelesaikan masalah fisika mekanika. Menggunakan software aljabar komputer.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 5,
            'jumlah_halaman' => 210,
            'semester' => '4',
        ]);

        Book::create([
            'judul_buku' => 'Environmental Physics',
            'nama_penulis' => 'Clare Smith',
            'harga_beli' => 165000,
            'harga_sewa' => 41000,
            'stok_beli' => 3,
            'stok_sewa' => 1,
            'gambar_buku' => [
                'book-images/environmental-physics-front.jpg',
                'book-images/environmental-physics-back.jpg',
                'book-images/environmental-physics-in.jpg',
            ],
            'deskripsi_buku' => 'Fisika lingkungan yang membahas energi, polusi, dan perubahan iklim dari perspektif fisika. Edisi bahasa Inggris.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Jakarta Pusat, DKI Jakarta',
            'category_id' => 5,
            'jumlah_halaman' => 340,
            'semester' => '5',
        ]);

        Book::create([
            'judul_buku' => 'Fisika Universitas',
            'nama_penulis' => 'Frederick J. Bueche',
            'harga_beli' => 155000,
            'harga_sewa' => 40000,
            'stok_beli' => 9,
            'stok_sewa' => 4,
            'gambar_buku' => [
                'book-images/fisika-universitas-front.jpg',
                'book-images/fisika-universitas-back.jpg',
                'book-images/fisika-universitas-in.jpg',
            ],
            'deskripsi_buku' => 'Buku referensi standar fisika universitas Seri Schaum. Berisi ringkasan teori dan ribuan soal latihan.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Semarang, Jawa Tengah',
            'category_id' => 5,
            'jumlah_halaman' => 500,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Fisika untuk Universitas 1',
            'nama_penulis' => 'Sears Zemanzky',
            'harga_beli' => 160000,
            'harga_sewa' => 42000,
            'stok_beli' => 10,
            'stok_sewa' => 5,
            'gambar_buku' => [
                'book-images/fisika-untuk-universitas-1-front.jpg',
                'book-images/fisika-untuk-universitas-1-back.jpg',
                'book-images/fisika-untuk-universitas-1-in.jpg',
            ],
            'deskripsi_buku' => 'Jilid pertama dari seri Fisika Universitas yang legendaris. Pembahasan mendalam mekanika klasik.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 5,
            'jumlah_halaman' => 480,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Kimia Dasar 3',
            'nama_penulis' => 'Syukri S.',
            'harga_beli' => 98000,
            'harga_sewa' => 24000,
            'stok_beli' => 8,
            'stok_sewa' => 3,
            'gambar_buku' => [
                'book-images/kimia-dasar-3-front.jpg',
                'book-images/kimia-dasar-3-back.jpg',
                'book-images/kimia-dasar-3-in.jpg',
            ],
            'deskripsi_buku' => 'Buku kimia dasar jilid 3 karya penulis Indonesia. Bahasa mudah dipahami, cocok untuk mahasiswa tahun pertama.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Padang, Sumatera Barat',
            'category_id' => 6,
            'jumlah_halaman' => 220,
            'semester' => '2',
        ]);

        Book::create([
            'judul_buku' => 'Kimia Dasar',
            'nama_penulis' => 'Ralph H. Petrucci',
            'harga_beli' => 170000,
            'harga_sewa' => 45000,
            'stok_beli' => 5,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/kimia-dasar-front.jpg',
                'book-images/kimia-dasar-back.jpg',
                'book-images/kimia-dasar-in.jpg',
            ],
            'deskripsi_buku' => 'Prinsip-prinsip dan aplikasi modern kimia dasar. Buku terjemahan yang menjadi acuan di banyak universitas.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Jakarta Timur, DKI Jakarta',
            'category_id' => 6,
            'jumlah_halaman' => 450,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'General Chemistry',
            'nama_penulis' => 'Ebbing',
            'harga_beli' => 185000,
            'harga_sewa' => 47000,
            'stok_beli' => 3,
            'stok_sewa' => 1,
            'gambar_buku' => [
                'book-images/general-chemistry-front.jpg',
                'book-images/general-chemistry-back.jpg',
                'book-images/general-chemistry-in.jpg',
            ],
            'deskripsi_buku' => 'Buku teks kimia umum (General Chemistry) edisi internasional. Lengkap dengan ilustrasi molekuler yang jelas.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Surabaya, Jawa Timur',
            'category_id' => 6,
            'jumlah_halaman' => 520,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Pengembangan Program Pengajaran Bidang Studi Kimia',
            'nama_penulis' => 'Marlon Dumas',
            'harga_beli' => 105000,
            'harga_sewa' => 26000,
            'stok_beli' => 6,
            'stok_sewa' => 3,
            'gambar_buku' => [
                'book-images/pengembangan-program-pengajaran-bidang-studi-kimia-front.jpg',
                'book-images/pengembangan-program-pengajaran-bidang-studi-kimia-back.jpg',
                'book-images/pengembangan-program-pengajaran-bidang-studi-kimia-in.jpg',
            ],
            'deskripsi_buku' => 'Panduan bagi calon pendidik kimia dalam menyusun kurikulum dan metode pengajaran yang efektif.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Yogyakarta, DI Yogyakarta',
            'category_id' => 6,
            'jumlah_halaman' => 240,
            'semester' => '5',
        ]);

        Book::create([
            'judul_buku' => 'Kimia Koordinasi',
            'nama_penulis' => 'Prof. Dr. Sukardjo',
            'harga_beli' => 115000,
            'harga_sewa' => 29000,
            'stok_beli' => 4,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/kimia-koordinasi-front.jpg',
                'book-images/kimia-koordinasi-back.jpg',
                'book-images/kimia-koordinasi-in.jpg',
            ],
            'deskripsi_buku' => 'Membahas senyawa kompleks dan ikatan koordinasi. Materi penting untuk kimia anorganik lanjut.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Yogyakarta, DI Yogyakarta',
            'category_id' => 6,
            'jumlah_halaman' => 260,
            'semester' => '4',
        ]);

        Book::create([
            'judul_buku' => 'Advanced Concepts in Physical Chemistry',
            'nama_penulis' => 'Kaufman',
            'harga_beli' => 195000,
            'harga_sewa' => 49000,
            'stok_beli' => 2,
            'stok_sewa' => 1,
            'gambar_buku' => [
                'book-images/advanced-concepts-in-physical-chemistry-front.jpg',
                'book-images/advanced-concepts-in-physical-chemistry-back.jpg',
                'book-images/advanced-concepts-in-physical-chemistry-in.jpg',
            ],
            'deskripsi_buku' => 'Konsep tingkat lanjut dalam kimia fisik. Ditujukan untuk mahasiswa tingkat akhir atau pascasarjana.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Jakarta Selatan, DKI Jakarta',
            'category_id' => 6,
            'jumlah_halaman' => 410,
            'semester' => '7',
        ]);

        Book::create([
            'judul_buku' => 'Kimia Dasar',
            'nama_penulis' => 'Ita Ulfin',
            'harga_beli' => 85000,
            'harga_sewa' => 20000,
            'stok_beli' => 12,
            'stok_sewa' => 5,
            'gambar_buku' => [
                'book-images/kimia-dasar-1-front.jpg',
                'book-images/kimia-dasar-1-back.jpg',
                'book-images/kimia-dasar-1-in.jpg',
            ],
            'deskripsi_buku' => 'Pengantar ilmu kimia yang ringkas dan padat. Cocok sebagai buku pegangan awal kuliah.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Surabaya, Jawa Timur',
            'category_id' => 6,
            'jumlah_halaman' => 190,
            'semester' => '1',
        ]);

        Book::create([
            'judul_buku' => 'Chromatographic Separations',
            'nama_penulis' => 'Peter A. Sewell',
            'harga_beli' => 145000,
            'harga_sewa' => 36000,
            'stok_beli' => 3,
            'stok_sewa' => 1,
            'gambar_buku' => [
                'book-images/chromatographic-separations-front.jpg',
                'book-images/chromatographic-separations-back.jpg',
                'book-images/chromatographic-separations-in.jpg',
            ],
            'deskripsi_buku' => 'Teori dan praktik pemisahan kromatografi. Sangat berguna untuk kimia analisis dan laboratorium.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 6,
            'jumlah_halaman' => 300,
            'semester' => '5',
        ]);

        Book::create([
            'judul_buku' => 'Dasar-dasar Kimia Anorganik Transisi',
            'nama_penulis' => 'Kristian H. Sugiyarto',
            'harga_beli' => 125000,
            'harga_sewa' => 31000,
            'stok_beli' => 5,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/dasar-dasar-kimia-anorganik-transisi-front.jpg',
                'book-images/dasar-dasar-kimia-anorganik-transisi-back.jpg',
                'book-images/dasar-dasar-kimia-anorganik-transisi-in.jpg',
            ],
            'deskripsi_buku' => 'Fokus pada kimia unsur-unsur transisi, sifat magnetik, dan spektrum elektronik. Buku wajib kimia anorganik.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Yogyakarta, DI Yogyakarta',
            'category_id' => 6,
            'jumlah_halaman' => 280,
            'semester' => '4',
        ]);

        Book::create([
            'judul_buku' => 'Geologi Lingkungan',
            'nama_penulis' => 'Djauhari Noor',
            'harga_beli' => 135000,
            'harga_sewa' => 34000,
            'stok_beli' => 4,
            'stok_sewa' => 1,
            'gambar_buku' => [
                'book-images/geologi-lingkungan-front.jpg',
                'book-images/geologi-lingkungan-back.jpg',
                'book-images/geologi-lingkungan-in.jpg',
            ],
            'deskripsi_buku' => 'Interaksi manusia dengan lingkungan geologis. Membahas bencana alam, sumber daya, dan pengelolaan lingkungan.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Bogor, Jawa Barat',
            'category_id' => 6,
            'jumlah_halaman' => 320,
            'semester' => '3',
        ]);

        Book::create([
            'judul_buku' => 'Komunikasi Data',
            'nama_penulis' => 'Abdi',
            'harga_beli' => 95000,
            'harga_sewa' => 23000,
            'stok_beli' => 9,
            'stok_sewa' => 4,
            'gambar_buku' => [
                'book-images/komunikasi-data-front.jpg',
                'book-images/komunikasi-data-back.jpg',
                'book-images/komunikasi-data-in.jpg',
            ],
            'deskripsi_buku' => 'Konsep dasar pengiriman data digital. Transmisi, sinyal, dan protokol komunikasi sederhana.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Makassar, Sulawesi Selatan',
            'category_id' => 8,
            'jumlah_halaman' => 210,
            'semester' => '3',
        ]);

        Book::create([
            'judul_buku' => 'Komunikasi Data & Jaringan Komputer',
            'nama_penulis' => 'Edhy Sutanta',
            'harga_beli' => 110000,
            'harga_sewa' => 27000,
            'stok_beli' => 7,
            'stok_sewa' => 3,
            'gambar_buku' => [
                'book-images/komunikasi-data-jaringan-komputer-front.jpg',
                'book-images/komunikasi-data-jaringan-komputer-back.jpg',
                'book-images/komunikasi-data-jaringan-komputer-in.jpg',
            ],
            'deskripsi_buku' => 'Integrasi antara komunikasi data dan implementasinya dalam jaringan komputer modern. Mencakup model OSI dan TCP/IP.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Yogyakarta, DI Yogyakarta',
            'category_id' => 8,
            'jumlah_halaman' => 350,
            'semester' => '4',
        ]);

        Book::create([
            'judul_buku' => 'Kriptografi untuk Keamanan Jaringan',
            'nama_penulis' => 'Fiqih Sadikin',
            'harga_beli' => 140000,
            'harga_sewa' => 35000,
            'stok_beli' => 5,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/kriptografi-untuk-keamanan-jaringan-back.jpg',
                'book-images/kriptografi-untuk-keamanan-jaringan-in.jpg',
            ],
            'deskripsi_buku' => 'Teknik enkripsi dan dekripsi untuk mengamankan data dalam jaringan. Membahas algoritma klasik hingga modern.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 8,
            'jumlah_halaman' => 300,
            'semester' => '6',
        ]);

        Book::create([
            'judul_buku' => 'Konsep Sistem Informasi',
            'nama_penulis' => 'Bambang Wahyudi, S.Kom, MMSi.',
            'harga_beli' => 100000,
            'harga_sewa' => 25000,
            'stok_beli' => 11,
            'stok_sewa' => 4,
            'gambar_buku' => [
                'book-images/konsep-sistem-informasi-front.jpg',
                'book-images/konsep-sistem-informasi-back.jpg',
                'book-images/konsep-sistem-informasi-in.jpg',
            ],
            'deskripsi_buku' => 'Pemahaman dasar mengenai komponen, fungsi, dan peran sistem informasi dalam organisasi.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Depok, Jawa Barat',
            'category_id' => 8,
            'jumlah_halaman' => 230,
            'semester' => '2',
        ]);

        Book::create([
            'judul_buku' => 'Teori Peluang',
            'nama_penulis' => 'RK. Sembiring',
            'harga_beli' => 90000,
            'harga_sewa' => 22000,
            'stok_beli' => 6,
            'stok_sewa' => 2,
            'gambar_buku' => [
                'book-images/teori-peluang-front.jpg',
                'book-images/teori-peluang-back.jpg',
                'book-images/teori-peluang-in.jpg',
            ],
            'deskripsi_buku' => 'Buku teks statistika matematika tentang probabilitas. Dasar penting untuk statistika lanjut dan aktuaria.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 7,
            'jumlah_halaman' => 200,
            'semester' => '3',
        ]);

        // Buku 2: Stok Habis (Untuk Tes Tampilan Abu-abu)
        Book::create([
            'judul_buku' => 'Buku Habis Stok',
            'nama_penulis' => 'Penulis Kosong',
            'harga_beli' => 60000.00,
            'harga_sewa' => 20000.00,
            'stok_beli' => 0,
            'stok_sewa' => 0,
            'gambar_buku' => ['https://placehold.co/270x480/3B82F6/white?text=Habis'],
            'deskripsi_buku' => 'Buku ini stoknya habis.',
            'user_id' => $sellers[$i++]->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Jakarta, DKI Jakarta',
            'category_id' => 3,
            'jumlah_halaman' => 250,
            'semester' => '5',
        ]);

        // Buat 10 buku acak lainnya via Factory
        Book::factory(10)->create([
            'user_id' => User::inRandomOrder()->first()->id
        ]);

        // Buat Reviews
        $allBooks = Book::all();
        foreach ($allBooks as $book) {
            $numberOfReviews = rand(10, 25);
            for ($j = 0; $j < $numberOfReviews; $j++) {
                Review::factory()->create([
                    'book_id' => $book->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                ]);
            }
        }
    }
}
