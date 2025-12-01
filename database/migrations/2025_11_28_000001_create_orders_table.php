<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Pastikan tabel 'users' dan 'books' sudah ada sebelum ini dijalankan (karena timestamp lebih baru)
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');

            $table->enum('type', ['beli', 'sewa']);
            $table->decimal('price', 10, 2);

            $table->enum('status', ['Packing', 'Picked', 'In Transit', 'Delivered', 'Cancelled'])->default('Packing');
            $table->integer('rating')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
