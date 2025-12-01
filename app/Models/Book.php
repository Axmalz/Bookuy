<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'gambar_buku' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function reviews() { return $this->hasMany(Review::class); }

    // Scope: Urutkan agar buku yang stoknya habis (beli 0 DAN sewa 0) ada di paling bawah
    // Prioritas 1: Ada stok (beli atau sewa > 0)
    // Prioritas 2: Stok habis (beli 0 dan sewa 0)
    public function scopeOrderByStockAvailability($query)
    {
        return $query->orderByRaw('CASE WHEN (stok_beli > 0 OR stok_sewa > 0) THEN 1 ELSE 2 END');
    }
}
