<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\Review;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Penjual Acak
        $randomSeller1 = User::inRandomOrder()->first() ?? User::factory()->create();

        // Buku 1: Stok Ada
        Book::create([
            'judul_buku' => 'Fundamental MPB',
            'nama_penulis' => 'Marlon Dumas',
            'harga_beli' => 50000.00,
            'harga_sewa' => 15000.00,
            'stok_beli' => 10,
            'stok_sewa' => 5,
            'gambar_buku' => [
                'https://placehold.co/270x480/EAB308/white?text=Depan',
                'https://placehold.co/270x480/CA8A04/white?text=Samping',
                'https://placehold.co/270x480/A16207/white?text=Belakang'
            ],
            'deskripsi_buku' => 'Deskripsi lengkap buku Fundamental MPB.',
            'user_id' => $randomSeller1->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Surabaya, Jawa Timur',
            'category_id' => 1,
            'jumlah_halaman' => 300,
            'semester' => '3',
        ]);

                // 2. Sistem Enterprise
        $randomSeller2 = User::inRandomOrder()->first();
        Book::create([
            'judul_buku' => 'Sistem Enterprise',
            'nama_penulis' => 'Mahendrawati EP, Ph.D.',
            'harga_beli' => 60000.00,
            'harga_sewa' => 20000.00,
            // STOK
            'stok_beli' => 10,
            'stok_sewa' => 5,
            'gambar_buku' => ['https://placehold.co/270x480/3B82F6/white?text=Depan'],
            'deskripsi_buku' => 'Deskripsi lengkap buku Sistem Enterprise.',
            'user_id' => $randomSeller2->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Jakarta, DKI Jakarta',
            'category_id' => 3,
            'jumlah_halaman' => 250,
            'semester' => '5',
        ]);

        // 3. Matematika
        $randomSeller3 = User::inRandomOrder()->first();
        Book::create([
            'judul_buku' => 'Matematika',
            'nama_penulis' => 'Jon Yablonski',
            'harga_beli' => 45000.00,
            'harga_sewa' => 15000.00,
            // STOK
            'stok_beli' => 10,
            'stok_sewa' => 5,
            'gambar_buku' => ['https://placehold.co/270x480/22C55E/white?text=Matematika'],
            'deskripsi_buku' => 'Buku pengantar matematika lengkap untuk mahasiswa teknik.',
            'user_id' => $randomSeller3->id,
            'kondisi_buku' => 'bekas usang',
            'alamat_buku' => 'Bandung, Jawa Barat',
            'category_id' => 4,
            'jumlah_halaman' => 400,
            'semester' => '1',
        ]);

        // 4. Fisika Dasar 1
        $randomSeller4 = User::inRandomOrder()->first();
        Book::create([
            'judul_buku' => 'Fisika Dasar 1',
            'nama_penulis' => 'Donelly Reksay',
            'harga_beli' => 95000.00,
            'harga_sewa' => 30000.00,
            // STOK
            'stok_beli' => 10,
            'stok_sewa' => 5,
            'gambar_buku' => ['https://placehold.co/270x480/0EA5E9/white?text=Fisika+1'],
            'deskripsi_buku' => 'Konsep dasar fisika mekanika dan termodinamika.',
            'user_id' => $randomSeller4->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Yogyakarta, DIY',
            'category_id' => 5,
            'jumlah_halaman' => 350,
            'semester' => '1',
        ]);

        // 5. Pemrograman Web Dasar
        $randomSeller5 = User::inRandomOrder()->first();
        Book::create([
            'judul_buku' => 'Pemrograman Web Dasar',
            'nama_penulis' => 'Jon Yablonski',
            'harga_beli' => 70000.00,
            'harga_sewa' => 25000.00,
            // STOK
            'stok_beli' => 10,
            'stok_sewa' => 5,
            'gambar_buku' => ['https://placehold.co/270x480/F97316/white?text=Web+Dev'],
            'deskripsi_buku' => 'Belajar HTML, CSS, dan JavaScript dasar dari nol.',
            'user_id' => $randomSeller5->id,
            'kondisi_buku' => 'bekas premium',
            'alamat_buku' => 'Malang, Jawa Timur',
            'category_id' => 2,
            'jumlah_halaman' => 280,
            'semester' => '2',
        ]);

        // Buku 2: Stok Habis (Untuk Tes Tampilan Abu-abu)
        $randomSeller2 = User::inRandomOrder()->first();
        Book::create([
            'judul_buku' => 'Buku Habis Stok',
            'nama_penulis' => 'Penulis Kosong',
            'harga_beli' => 60000.00,
            'harga_sewa' => 20000.00,
            'stok_beli' => 0,
            'stok_sewa' => 0,
            'gambar_buku' => ['https://placehold.co/270x480/3B82F6/white?text=Habis'],
            'deskripsi_buku' => 'Buku ini stoknya habis.',
            'user_id' => $randomSeller2->id,
            'kondisi_buku' => 'baru',
            'alamat_buku' => 'Jakarta, DKI Jakarta',
            'category_id' => 3,
            'jumlah_halaman' => 250,
            'semester' => '5',
        ]);

        // Buat 10 buku acak lainnya via Factory
        // Pastikan Factory juga diupdate untuk stok random (sudah ada di langkah sebelumnya)
        Book::factory(10)->create([
            'user_id' => User::inRandomOrder()->first()->id
        ]);

        // Buat Reviews
        $allBooks = Book::all();
        foreach ($allBooks as $book) {
            $numberOfReviews = rand(3, 10);
            for ($j = 0; $j < $numberOfReviews; $j++) {
                Review::factory()->create([
                    'book_id' => $book->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                ]);
            }
        }
    }
}
