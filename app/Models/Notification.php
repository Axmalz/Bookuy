<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Pastikan fillable atau guarded diatur
    protected $guarded = [];

    // Relasi balik ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
