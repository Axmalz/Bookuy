<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Manajemen Proses Bisnis', 'icon_path' => 'images/icon-kategori-manajemen.png'],
            ['name' => 'Pemrograman', 'icon_path' => 'images/icon-kategori-pemrograman.png'],
            ['name' => 'Sistem Enterprise', 'icon_path' => 'images/icon-kategori-sistem.png'],
            ['name' => 'Matematika', 'icon_path' => 'images/icon-kategori-matematika.png'],
            ['name' => 'Fisika', 'icon_path' => 'images/icon-kategori-fisika.png'],
            ['name' => 'Kimia', 'icon_path' => 'images/icon-kategori-kimia.png'],
            ['name' => 'Makalah', 'icon_path' => 'images/icon-kategori-makalah.png'],
            ['name' => 'Database', 'icon_path' => 'images/icon-kategori-database.png'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
