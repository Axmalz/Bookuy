<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk waktu (e.g., "2 days ago")
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
