<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Helper untuk menghitung subtotal item ini
    public function getSubtotalAttribute()
    {
        $price = ($this->type == 'sewa') ? $this->book->harga_sewa : $this->book->harga_beli;
        return $price * $this->quantity;
    }
}
