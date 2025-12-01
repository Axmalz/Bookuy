<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku');
            $table->string('nama_penulis');
            $table->decimal('harga_beli', 10, 2);
            $table->decimal('harga_sewa', 10, 2);

            // Stok
            $table->integer('stok_beli')->default(0);
            $table->integer('stok_sewa')->default(0);

            $table->text('gambar_buku'); // JSON Array
            $table->text('deskripsi_buku');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('kondisi_buku', ['baru', 'bekas premium', 'bekas usang']);
            $table->string('alamat_buku');
            $table->foreignId('category_id')->constrained('categories');
            $table->integer('jumlah_halaman');
            $table->enum('semester', ['1', '2', '3', '4', '5', '6', '7', '8', 'tidak ada'])->default('tidak ada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
