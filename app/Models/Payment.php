<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk format nomor kartu tersembunyi (**** **** **** 1234)
    public function getMaskedNumberAttribute()
    {
        $last4 = substr($this->card_number, -4);
        return "**** **** **** " . $last4;
    }
}
